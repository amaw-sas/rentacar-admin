<?php

use App\Http\Controllers\Dummy\DummyVehicleAvailableRateController;
use App\Http\Controllers\Dummy\DummyVehicleReserveController;
use Illuminate\Support\Facades\Route;

Route::controller(DummyVehicleAvailableRateController::class)->group(function(){
    Route::post('/disponibilidad','index')->name('dummy.disponibilidad');
    Route::post('/disponibilidad/sindisponibilidad','no_categories_available')->name('dummy.sin_disponibilidad');
    Route::post('/disponibilidad/horadevolucionfuerahorario','hora_devolucion_fuera_horario')->name('dummy.hora_devolucion_fuera_horario');
    Route::post('/disponibilidad/fechadevolucionfuerahorario','fecha_devolucion_fuera_horario')->name('dummy.fecha_devolucion_fuera_horario');
    Route::post('/disponibilidad/horarecogidafuerahorario','hora_recogida_fuera_horario')->name('dummy.hora_recogida_fuera_horario');
    Route::post('/disponibilidad/fecharecogidafuerahorario','fecha_recogida_fuera_horario')->name('dummy.fecha_recogida_fuera_horario');
    Route::post('/disponibilidad/errordesconocido','error_desconocido')->name('dummy.error_desconocido');
    Route::post('/disponibilidad/horasextra','horas_extra')->name('dummy.horas_extra');
    Route::post('/disponibilidad/tasaretorno','tasa_retorno')->name('dummy.tasa_retorno');
    Route::post('/disponibilidad/timeout','timeout')->name('dummy.timeout');
    Route::post('/disponibilidad/emptyarray','empty_array')->name('dummy.empty_array');
});

Route::controller(DummyVehicleReserveController::class)->group(function(){
    Route::post('/subscribe/formlead', 'index')->name('dummy.reserve.index');
    Route::post('/subscribe/formlead/reservado', 'reservado')->name('dummy.reserve.reservado');
    Route::post('/subscribe/formlead/pendiente', 'pendiente')->name('dummy.reserve.pendiente');
    Route::post('/subscribe/formlead/errordesconocido', 'error_desconocido')->name('dummy.reserve.errordesconocido');
    Route::post('/subscribe/formlead/timeout', 'timeout')->name('dummy.reserve.timeout');
});

