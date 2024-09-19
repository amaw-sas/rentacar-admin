<?php

namespace App\Traits;

trait MultipleAttributeSetter
{
    private array $attributes;
    private array $undefinedAttributes;

    /**
     * Establece múltiples atributos a la vez, manejando tanto propiedades definidas como atributos dinámicos.
     *
     * @param array $attributes Un array de atributos donde las claves son los nombres de los atributos y los valores son los valores correspondientes.
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            // Para los atributos específicos, usamos los setters definidos si existen
            if (property_exists($this, $key)) {
                $method = 'set' . ucfirst($key);
                $this->attributes[$key] = $value;
                if (method_exists($this, $method)) {
                    $this->$method($value);
                } else {
                    $this->$key = $value;
                }
            } else {
                // Para cualquier otro atributo, lo agregamos a un array de atributos dinámicos
                // Aquí deberías tener una propiedad privada $attributes o similar definida en la clase que use este trait.
                if (isset($this->undefinedAttributes)) {
                    $this->undefinedAttributes[$key] = $value;
                } else {
                    throw new \Exception("La propiedad \$undefinedAttributes no está definida para manejar atributos dinámicos.");
                }
            }
        }
    }
}
