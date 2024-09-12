<?php

$noAvailableCategoriesError = <<<EOT
Lo sentimos, No se encontraron vehículos disponibles, inténta cambiando el día o la sede de recogida
EOT;

$unknownError = <<<EOT
Ha ocurrido un error inesperado, por favor contacte a nuestros asesores
EOT;

$outOfSchedulePickupHour = <<<EOT
La hora de recogida está por fuera del horario de atención de la sede seleccionada
EOT;

$outOfScheduleReturnHour = <<<EOT
La hora de devolución está por fuera del horario de atención de la sede seleccionada
EOT;

$outOfSchedulePickupDate = <<<EOT
El día de recogida está por fuera del horario de atención de la sede seleccionada
EOT;

$outOfScheduleReturnDate = <<<EOT
El día de devolución está por fuera del horario de atención de la sede seleccionada
EOT;

$sameHour = <<<EOT
El día y hora de recogida son iguales a los de devolución
EOT;

$inferiorPickupDate = <<<EOT
Selecciona la hora de recogida con una o dos horas posteriores a la hora actual
EOT;

$inferiorReturnDate = <<<EOT
Selecciona la hora de devolución con una o dos horas posteriores a la hora actual
EOT;

$holidayPickupDateBranch = <<<EOT
El lugar de recogida no funciona en esa fecha por ser día festivo
EOT;

$holidayReturnDateBranch = <<<EOT
El lugar de devolución no funciona en esa fecha por ser día festivo
EOT;

$holidayOutOfSchedulePickupDateBranch = <<<EOT
El lugar de recogida está por fuera del horario de atención de la sede seleccionada por ser día festivo
EOT;

$holidayOutOfScheduleReturnDateBranch = <<<EOT
El lugar de devolución está por fuera del horario de atención de la sede seleccionada por ser día festivo
EOT;

$errorSavingReservation = <<<EOT
No se pudo guardar la solicitud de reserva
EOT;

$errorSendingReservationRequestToLocaliza = <<<EOT
No se pudo enviar la solicitud de reserva a la mesa de reserva del proveedor
EOT;

return [
    'connection_timeout'        =>  'El servicio de conexión con el proveedor de vehículos no responde. Por favor contáctenos y reportenos del error',
    'client_error'              =>  'Ha ocurrido un error en nuestro servicio. Por favor contáctenos y reportenos del error',
    'server_error'              =>  'Ha ocurrido un error en el servicio de proveedor de vehículos. Por favor contáctenos y reportenos del error',
    'category_not_found'        =>  'No se ha encontrado disponibilidad de vehículos con esas condiciones. Por favor contáctenos y reportenos del error',
    'data_not_found'            =>  'No se ha podido localizar los datos de precios de su solicitud. Por favor contáctenos y reportenos del error',
    'prices_not_found'          =>  'No se ha podido calcular los precios de su solicitud. Por favor contáctenos y reportenos del error',
    'currency_exchange_error'   =>  'No se ha podido convertir los precios de su solicitud a COP. Por favor contáctenos y reportenos del error',
    'unknown_error'             =>  $unknownError,
    'no_available_categories_error' => $noAvailableCategoriesError,
    'out_of_schedule_pickup_hour_error'     =>  $outOfSchedulePickupHour,
    'out_of_schedule_return_hour_error'     =>  $outOfScheduleReturnHour,
    'out_of_schedule_pickup_date_error'     =>  $outOfSchedulePickupDate,
    'out_of_schedule_return_date_error'     =>  $outOfScheduleReturnDate,
    'same_hour'                 =>  $sameHour,
    'inferior_pickup_date'                 =>  $inferiorPickupDate,
    'inferior_return_date'                 =>  $inferiorReturnDate,
    'holiday_pickup_date_branch'            => $holidayPickupDateBranch,
    'holiday_return_date_branch'            => $holidayReturnDateBranch,
    'error_saving_reservation'              => $errorSavingReservation,
    'error_sending_reservation_request_to_localiza' => $errorSendingReservationRequestToLocaliza,
    'holiday_out_of_schedule_pickup_date_branch' => $holidayOutOfSchedulePickupDateBranch,
    'holiday_out_of_schedule_return_date_branch' => $holidayOutOfScheduleReturnDateBranch,
    'no_reserve_code'           => "No se pudo obtener el código de reserva",
    'no_reservation_status'           => "No se pudo obtener el estado de la reserva",
];
