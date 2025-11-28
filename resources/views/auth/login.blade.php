<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
    <h1>Ingreso</h1>
    @if($errors->any())
      <div style="color:red">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <label>Usuario (email):</label>
        <input name="usuario" value="{{ old('usuario') }}" required />
        <br/>
        <label>Contraseña:</label>
        <input name="password" type="password" required />
        <br/>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
