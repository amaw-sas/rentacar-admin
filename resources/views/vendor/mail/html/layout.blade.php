<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
 /* Es mejor usar estilos inline para mayor compatibilidad, pero aquí puedes poner algo de CSS básico para Outlook */
 @media only screen and (max-width: 600px) {
            .content { width: 100% !important; }
        }
</style>
</head>
<body style="margin: 0; padding: 0; background-color: #f2f2f2;">

<br/>

{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}


<br/>
</body>
</html>
