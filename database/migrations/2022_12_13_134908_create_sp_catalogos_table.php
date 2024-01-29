<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpCatalogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp_catalogos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cargo',200);
            $table->string('concepto',255);
            $table->string('cca',100)->nullable();
            $table->integer('departamento_id')->nullable();
            $table->timestamps();
        });

        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'PROYECTO EJECUTIVO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'LEVANTAMIENTO TOPOGRÁFICO  Y CURVAS DE NIVEL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'PLANO DE RASANTES'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'ESTUDIO DE MECÁNICA DE SUELOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'MEMORIAS CÁLCULO ESTRUCTURAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'MEMORIAS DE REDES'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'PROYECTO ARQUITECTÓNICO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'REGISTRO DE FRACCIONAMIENTO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'RUV'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'CUV'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PROYECTO', 'concepto'=>'OTROS'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'PERMISOS DE LOTIFICACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'NÚMERO OFICIAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'ALINEAMIENTO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'LICENCIA DE CONSTRUCCIÓN, MULTAS Y RECARGOS.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'APORTACION AL ICIC'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'REGIMEN DE CONDOMINIO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'AUTORIZACIÓN DEFINITIVA ESTATAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'CUOTA SINDICAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'PERITAJE'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'REGISTRO DE CMIC'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'SUPERVISIÓN INTERAPAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'DESLINDE CATASTRAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'RELOTIFICACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'APORTACIÓN A CANADEVI'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'FACTIBILIDAD DE FRACCIONAMIENTO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'DICTAMEN URBANO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'EVALUACIÓN Y RESOLUCIÓN DE IMPACTO AMBIENTAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'BOMBEROS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'AVISO TERMINACIÓN DE OBRA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'ACTA ENTREGA-RECEPCIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'ALTA DE CLAVE CATASTRAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'LICENCIA DE USO DE SUELO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'APORTACIONES A CFE'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'DERECHOS DE CONEXIÓN DE AGUA Y DRENAJE (INTERAPAS)'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'ESTUDIOS DE FACTIBILIDAD INTERAPAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'OBRAS ADICIONALES A INTERAPAS POR CONVENIO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'GASTOS E IMPUESTOS CORRESPONDIENTES A LA ESCRITURACIÓN DEL ÁREA DE DONACIÓN.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'PERMISOS, LICENCIAS Y AUTORIZACIONES MUNICIPALES', 'concepto'=>'OTROS'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'TERRENO', 'concepto'=>'VALOR DEL TERRENO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'TERRENO', 'concepto'=>'GASTOS NOTARIALES E IMPUESTOS DERIVADOS DE LA ADQUISICIÓN DEL TERRENO (ISAI, ISR).'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'TERRENO', 'concepto'=>'AVALUO CATASTRAL POR COMPRA DE TERRENO.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'TERRENO', 'concepto'=>'LIBERTAD DE GRAVAMEN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'TERRENO', 'concepto'=>'DERECHOS DE REGISTRO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'TERRENO', 'concepto'=>'HONORARIOS PROPIOS DEL TERRENO.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'TERRENO', 'concepto'=>'OTROS'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'URBANIZACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'OBRA CIVIL DE ELECTRIFICACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'OBRAS ESPECIALES DE ELECTRIFICACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'OBRA PARA CABLECOM'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'OBRA PARA TELMEX'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'OBRA PARA RED DE ALUMBRADO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'EQUIPAMIENTO Y MOBILIARIO (SEÑALIZACIÓN)'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'ACCESOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'OBRAS EXTRAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'TRABAJOS DE TERRACERÍAS, PLATAFORMAS Y VIALIDAD'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'PAVIMENTACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'URBANIZACIÓN E INFRAESTRUCTURA', 'concepto'=>'PLATAFORMAS EN ÁREA DE VIVIENDA'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'EDIFICACIÓN DE VIVIENDA', 'concepto'=>'EDIFICACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EDIFICACIÓN DE VIVIENDA', 'concepto'=>'ADMINISTRACIÓN DE OBRA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EDIFICACIÓN DE VIVIENDA', 'concepto'=>'SUPERVISIÓN INTERNA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EDIFICACIÓN DE VIVIENDA', 'concepto'=>'OBRAS PROVISIONALES'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'FIANZAS Y SEGUROS', 'concepto'=>'FIANZAS PARA GARANTÍA DE CONSTRUCCIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'FIANZAS Y SEGUROS', 'concepto'=>'FIANZA PARA GARANTÍA DE DAÑOS Y PERJUICIOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'FIANZAS Y SEGUROS', 'concepto'=>'FIANZA PARA GARANTÍA DE TESORERÍA MUNICIPAL (25 % URB )'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'FIANZAS Y SEGUROS', 'concepto'=>'SEGURO CONTRA INCENDIOS Y RAYOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'FIANZAS Y SEGUROS', 'concepto'=>'FIANZA DEL TERRENO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'FIANZAS Y SEGUROS', 'concepto'=>'FIANZA ANTE INTERAPAS'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS FINANCIEROS', 'concepto'=>'GASTOS DE ESCRITURACIÓN CRÉDITO PUENTE'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS FINANCIEROS', 'concepto'=>'COMISIÓN INTEGRAL APERTURA CRÉDITO PUENTE'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS FINANCIEROS', 'concepto'=>'INTERESES CRÉDITO PUENTE'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS FINANCIEROS', 'concepto'=>'COMISIONES BANCARIAS'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'AREAS VERDES'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'ALBERCA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'GIMNASIO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'CASA CLUB'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'MULTICANCHAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'APARATOS DE EJERCICIO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'JUEGOS DE AREAS VERDE'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'SEÑALETICAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'TOPES O REDUCTORES DE VELOCIDAD'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'PASTO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'CASETAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'RIEGOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'PLANTAS, ARBOLES'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'CERCA PERIMETRAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'EQUIPAMIENTO', 'concepto'=>'CIRCUITO CERRADO DEL FRACCIONAMIENTO'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'LUZ'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'AGUA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'RENTAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'NÓMINA, BONOS E INCENTIVOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'TELÉFONO EN OFICINA Y CELULARES Y BAM.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'AGUINALDOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'VACACIONES'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'PRIMA VACACIONAL'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'GRATIFICACIONES'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'PAQUETERÍA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'COMBUSTIBLE'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'VIGILANCIA DE OFICINAS DE OPERACIÓN.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'ASEO Y LIMPIEZA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'ESTACIONAMIENTOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'CONSUMOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'TENENCIAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'MULTAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'OBSEQUIOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'PAPELERÍA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'GASTOS DE VIAJE Y/O VIATICOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'MANTENIMIENTO DE ACTIVO FIJO.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'IMPUESTOS Y DERECHOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'TODOS LOS HONORARIOS.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'DONATIVOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'EVENTOS.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS INDIRECTOS DE OPERACIÓN E INCENTIVOS', 'concepto'=>'CAPACITACIONES Y CERTIFICACIONES DE PERSONAL.'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'ANUNCIOS PUBLICITARIOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'RENDERS.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'ESPACIOS EN RADIO Y TV'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'ESTRUCTURAS METÁLICAS PARA ANUNCIOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'TARJETAS DE PRESENTACIÓN'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'AVALÚOS COMERCIALES'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'LONAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'MANTENIMIENTO Y LIMPIEZA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'MANTENIMIENTO DE EQUIPO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'VIGILANCIA DEL FRACCIONAMIENTO.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'MANTENIMIENTO DE CASAS DE FRACCIONAMIENTO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'MANTENIMIENTO DE FRACCIONAMIENTO.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'GASTOS DERIVADOS DE VENTA.'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'VIGILANCIA POR PROMOCION (ALARMAS)'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'EQUIPAMIENTO POR PROMOCION (COCINAS, CLOSET, CANCEL, PROTECCIONES, PORTONES, PERSIANAS)'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'CASAS MUESTRA. (TODO EL EQUIPAMIENTO DE LA CASA MUESTRA)'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'BONOS POR PROMOCION'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DE COMERCIALIZACIÓN', 'concepto'=>'USO DE LA MARCA, (REGALIAS GCC A CONCRETANIA)'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'ELECTRIFICACIÓN', 'concepto'=>'Suministros de electrificación (Cardentey).'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'LUZ'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'AGUA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'RENTAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'TELÉFONO EN OFICINA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'VIGILANCIA DE OFICINAS '));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'VIGILANCIA DE PRIVADAS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'ASEO Y LIMPIEZA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'MANTENIMIENTO DE ACTIVO FIJO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'HONORARIOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'GASTOS DIRECTOS DE OPERACIÓN', 'concepto'=>'EVENTOS'));

        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'COMPRA DE EQUIPOS DE COMPUTO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'COMPRA DE VEHICULOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'MOBILIARIO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'LICENCIAS DE COMPUTO'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'BODEGA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'DORMITORIOS'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'OFICINAS DE OBRA'));
        DB::table('sp_catalogos')->insert(array('cargo'=>'ACTIVO FIJO', 'concepto'=>'OTROS ( ANDAMIOS, ESCALERAS, ETC )'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_catalogos');
    }
}
