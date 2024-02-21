<fieldset>
    <legend>Información del Vendedor</legend>

    <label for="nombre">Nombre</label>
    <input 
        type="text"
        id="nombre"
        name="vendedor[nombre]"
        placeholder="Nombre del Vendedor"
        value="<?php echo s($vendedor->nombre); ?>">

    <label for="precio">Apellido</label>
    <input
        type="text"
        id="apellido"
        name="vendedor[apellido]"
        placeholder="Apellido del Vendedor"
        value="<?php echo s($vendedor->apellido); ?>">

    <label for="telefono">Teléfono</label>
    <input
        type="text"
        id="telefono"
        name="vendedor[telefono]"
        placeholder="Teléfono del Vendedor"
        value="<?php echo s($vendedor->telefono); ?>">
</fieldset>