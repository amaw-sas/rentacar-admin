<?php

namespace App\Traits;

trait MultipleAttributeSetter
{
    private array $attributes;

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
            }
        }
    }
}
