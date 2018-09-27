<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            Menú Principal
        </li>
        <li>
            <a href="{!! url('home') !!}">
                <i class="fa fa-home"></i>&nbsp;
                Inicio</a>
        </li>

        @if(in_array(session()->get("myUser")->perfil_id,array(1,2,3)))
            <li class="sidebar-brand tab">
                Operaciones
            </li>
            <li>
                <a href="{!! url('appear') !!}">
                    <i class="fa fa-calendar-check-o"></i>&nbsp;
                    Registrar Asistencia Diaria</a>
            </li>
            <li>
                <a href="{!! url('prod') !!}">
                    <i class="fa fa-cubes"></i>&nbsp;
                    Registrar Producción Diaria</a>
            </li>
        @endif


        @if(in_array(session()->get("myUser")->perfil_id,array(1,3)))
            <li class="sidebar-brand tab">
                Reportes
            </li>

            <li>
                <a href="{!! url('report1') !!}">
                    <i class="fa fa-line-chart"></i>&nbsp;
                    Asistencia y producción</a>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-calculator"></i>&nbsp;
                    Cálculo de Nómina</a>
            </li>
        @endif


        @if(in_array(session()->get("myUser")->perfil_id,array(1)))
            <li class="sidebar-brand tab">
                Administración
            </li>

            <li>
                <a href="{!! url('config') !!}">
                    <i class="fa fa-cog"></i>&nbsp;
                    Empresa</a>
            </li>

            <li>
                <a href="{!! url('users') !!}">
                    <i class="fa fa-user"></i>&nbsp;
                    Usuarios</a>
            </li>
           
            <li>
                <a href="{!! url('areaRole') !!}">
                    <i class="fa fa-sitemap"></i>&nbsp;
                    Departamentos y Cargos</a>
            </li>

            <li>
                <a href="{!! url('tableLine') !!}">
                    <i class="fa fa-th-large"></i>&nbsp;
                    Mesas y Líneas</a>
            </li>

            <li>
                <a href="{!! url('people') !!}">
                    <i class="fa fa-users"></i>&nbsp;
                    Empleados</a>
            </li>

            <li>
                <a href="{!! url('bonus') !!}">
                    <i class="fa fa-money"></i>&nbsp;
                    Bonos Especiales</a>
            </li>
        @endif
    </ul>
</div>