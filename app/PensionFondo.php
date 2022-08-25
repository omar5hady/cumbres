<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\PensionFondoPago;

class PensionFondo extends Model
{
    /* A list of fields that can be filled by the user. */
    protected $fillable = [
        'user_id', 'saldo'
    ];

    /**
     * It returns a paginated list of payments for a given fund, filtered by date range
     *
     * @param Request request The request object.
     */
    public function pagos(Request $request){
        /* Creating a query to the database, but not executing it. */
        $pagos = PensionFondoPago::where('fondo_id','=',$this->id);
        if($request->fechaIni != '')
            /* Adding a filter to the query. */
            $pagos = $pagos->whereBetween('fecha_movimiento', [$request->fechaIni, $request->fechaFin]);
        /* Adding a filter to the query, and then executing it. */
        $pagos = $pagos->orderBy('fecha_movimiento','desc')->paginate(10);
        return $pagos;
    }

    /**
     * The function usuario() returns the user that belongs to the personal
     *
     * @return The user_id of the user that created the post.
     */
    public function usuario(){
        return $this->belongsTo('App\Personal','user_id');
    }
}
