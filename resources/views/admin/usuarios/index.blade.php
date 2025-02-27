@extends('layouts._includes.admin.body')
@section('titulo', 'Listar Usuários')

@section('conteudo')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-2 page-title">Lista de Usuários</h2>
                <button class="btn btn-info mb-3" data-toggle="modal" data-target="#usuarioModal" onclick="abrirModalCriacao()">
                    Criar Usuário
                </button>
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Nível</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->id }}</td>
                                                <td>{{ $usuario->name }} {{ $usuario->sobrenome }}</td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>{{ $usuario->nivel }}</td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#usuarioModal" onclick="abrirModalEdicao({{ $usuario }})">
                                                        Editar
                                                    </button>
                                                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Criação/Edição -->
    <div class="modal fade" id="usuarioModal" tabindex="-1" role="dialog" aria-labelledby="usuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usuarioModalLabel">Gerenciar Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="usuarioForm" method="POST">
                        @csrf
                        <div id="methodField"></div> <!-- Para adicionar @method('PUT') dinamicamente -->

                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="sobrenome">Sobrenome</label>
                            <input type="text" class="form-control" id="sobrenome" name="sobrenome" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="nivel">Nível</label>
                            <select class="form-control" id="nivel" name="nivel">
                                <option value="admin">Admin</option>
                                <option value="usuario">Usuário</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function abrirModalCriacao() {
            document.getElementById('usuarioModalLabel').innerText = 'Criar Usuário';
            document.getElementById('usuarioForm').reset();
            document.getElementById('usuarioForm').action = "{{ route('admin.usuarios.store') }}";

            // Método padrão POST para criação
            document.getElementById('methodField').innerHTML = '';
        }

        function abrirModalEdicao(usuario) {
            document.getElementById('usuarioModalLabel').innerText = 'Editar Usuário';
            document.getElementById('usuarioForm').action = "{{ route('admin.usuarios.update', '') }}/" + usuario.id;

            // Adicionando o método PUT para edição
            document.getElementById('methodField').innerHTML = '@method("PUT")';

            document.getElementById('name').value = usuario.name;
            document.getElementById('sobrenome').value = usuario.sobrenome;
            document.getElementById('email').value = usuario.email;
            document.getElementById('nivel').value = usuario.nivel;
        }
    </script>
@endsection
