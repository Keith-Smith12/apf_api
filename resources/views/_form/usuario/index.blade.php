<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="sobrenome">Sobrenome</label>
            <input type="text" id="sobrenome" name="sobrenome" class="form-control" value="{{ old('sobrenome') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="nivel">Nível</label>
            <select id="nivel" name="nivel" class="form-control">
                <option value="1" {{ old('nivel') == 1 ? 'selected' : '' }}>1</option>
                <option value="2" {{ old('nivel') == 2 ? 'selected' : '' }}>2</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="password_confirmation">Confirmar Senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>
    </div>
</div>
