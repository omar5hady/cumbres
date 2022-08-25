<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('personal')->onDelete('cascade');

            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);
            $table->string('foto_user')->default('default-image.gif');
            $table->integer('rol_id')->unsigned();
            $table->foreign('rol_id')->references('id')->on('roles');

            $table->boolean('calendario')->default(0);
            $table->boolean('notifications')->default(0);

            //Menus principales
            $table->boolean('administracion')->default(0);
            $table->boolean('desarrollo')->default(0);
            $table->boolean('precios')->default(0);
            $table->boolean('obra')->default(0);
            $table->boolean('ventas')->default(0);
            $table->boolean('acceso')->default(0);
            $table->boolean('reportes')->default(0);
            $table->boolean('postventa')->default(0);

            $table->boolean('saldo')->default(0);
            $table->boolean('gestoria')->default(0);
            $table->boolean('comisiones')->default(0);


            //Administracion
            $table->boolean('personas')->default(0);
            $table->boolean('empresas')->default(0);
            $table->boolean('medios_public')->default(0);
            $table->boolean('departamentos')->default(0);
            $table->boolean('lugares_contacto')->default(0);
            $table->boolean('servicios')->default(0);
            $table->boolean('inst_financiamiento')->default(0);
            $table->boolean('tipos_credito')->default(0);
            $table->boolean('asig_servicios')->default(0);
            $table->boolean('mis_asesores')->default(0);
            $table->boolean('notaria')->default(0);
            $table->boolean('cuenta')->default(0);
            $table->boolean('proveedores')->default(0);
            $table->boolean('digital_campain')->default(0);

            //Desarrollo
            $table->boolean('fraccionamiento')->default(0);
            $table->boolean('etapas')->default(0);
            $table->boolean('modelos')->default(0);
            $table->boolean('lotes')->default(0);
            $table->boolean('asign_modelos')->default(0);
            $table->boolean('licencias')->default(0);
            $table->boolean('acta_terminacion')->default(0);
            $table->boolean('p_fraccionamiento')->default(0);
            $table->boolean('p_etapa')->default(0);
            $table->boolean('descarga_actas')->default(0);
            $table->boolean('ruv')->default(0);
            $table->boolean('seg_ruv')->default(0);

            //Precios
            $table->boolean('agregar_sobreprecios')->default(0);
            $table->boolean('precios_etapas')->default(0);
            $table->boolean('precios_viviendas')->default(0);
            $table->boolean('sobreprecios')->default(0);
            $table->boolean('paquetes')->default(0);
            $table->boolean('promociones')->default(0);

            //Obra
            $table->boolean('contratistas')->default(0);
            $table->boolean('ini_obra')->default(0);
            $table->boolean('aviso_obra')->default(0);
            $table->boolean('partidas')->default(0);
            $table->boolean('avance')->default(0);
            $table->boolean('estimaciones')->default(0);

            //Ventas
            $table->boolean('lotes_disp')->default(0);
            $table->boolean('mis_prospectos')->default(0);
            $table->boolean('simulacion_credito')->default(0);
            $table->boolean('hist_simulaciones')->default(0);
            $table->boolean('hist_creditos')->default(0);
            $table->boolean('contratos')->default(0);
            $table->boolean('docs')->default(0);
            $table->boolean('equipamientos')->default(0);
            $table->boolean('digital_lead')->default(0);
            $table->boolean('reubicaciones')->default(0);

            //Rentas
            $table->boolean('admin_rentas')->default(0);
            $table->boolean('pagos_rentas')->default(0);

            //Saldos
            $table->boolean('edo_cuenta')->default(0);
            $table->boolean('depositos')->default(0);
            $table->boolean('gastos_admn')->default(0);
            $table->boolean('cobro_credito')->default(0);
            $table->boolean('dev_cancel')->default(0);
            $table->boolean('dev_exc')->default(0);
            $table->boolean('ingresos_concretania')->default(0);
            $table->boolean('dev_virtual')->default(0);

            //Gestoria
            $table->boolean('expediente')->default(0);
            $table->boolean('asig_gestor')->default(0);
            $table->boolean('seg_tramite')->default(0);
            $table->boolean('avaluos')->default(0);
            $table->boolean('bonos_rec')->default(0);
            $table->boolean('int_cobros')->default(0);

            //Postventa
            $table->boolean('entregas')->default(0);

            //Comisiones
            $table->boolean('exp_comision')->default(0);
            $table->boolean('gen_comision')->default(0);
            $table->boolean('bono_com')->default(0);

            //Cotizador Lotes
           $table->boolean('calc_lotes')->default(0);
           $table->boolean('edit_cotizacion')->default(0);
           $table->boolean('opc_cotizador')->default(0);

           //CREDITOS PUENTE
           $table->boolean('bases')->default(0);
           $table->boolean('solic_credito_puente')->default(0);
           $table->boolean('seg_cp')->default(0);
           $table->boolean('edo_cta_bancrea')->default(0);

           //RH
           $table->boolean('vehiculos')->default(0);
           $table->boolean('mant_vehiculos')->default(0);
           $table->boolean('admin_mant_vehiculos')->default(0);
           $table->boolean('prestamos_personales')->default(0);
           $table->boolean('fondo_ahorro')->default(0);
           $table->boolean('fondo_pension')->default(0);

           //OFICINA
           $table->boolean('inventarios')->default(0);
           $table->boolean('prov_inventarios')->default(0);

            //Acceso
            $table->boolean('usuarios')->default(0);
            $table->boolean('roles')->default(0);

            //Reportes
            $table->boolean('mejora')->default(0);
            $table->boolean('rep_publi')->default(0);
            $table->boolean('rep_proy')->default(0);
            $table->boolean('inventario')->default(0);
            $table->boolean('rep_venta_canc')->default(0);
            $table->boolean('rep_acumulado')->default(0);
            $table->boolean('rep_ingresos')->default(0);
            $table->boolean('rep_escrituras')->default(0);
            $table->boolean('rep_leads')->default(0);
            $table->boolean('rep_entregas')->default(0);
            $table->boolean('rep_empresas')->default(0);

            $table->boolean('rep_asesores')->default(0);
            $table->boolean('rep_ini_term_ventas')->default(0);
            $table->boolean('rep_recursos_propios')->default(0);
            $table->boolean('rep_vent_modelos')->default(0);
            $table->boolean('rep_detalles_post')->default(0);

            $table->boolean('facturas')->default(0);

            //Pagos internos
            $table->boolean('seg_pago')->default(0);




            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
