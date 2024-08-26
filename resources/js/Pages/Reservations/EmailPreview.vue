<template>
  <div class="body">
    <div class="parent-container">
      <div class="aspect-ratio-box-inner">
        <div
          class="container-white"
          style="
            margin-top: 20%;
            position: relative;
            display: flex;
            gap: 2%;
            align-items: center;
            flex-direction: column;
          "
        >
          <div
            style="
              position: absolute;
              width: 25%;
              top: -15%;
              padding: 3% 3% 0 3%;
              background-color: #ffffff;
            "
          >
            <img width="70" class="img-fluid" :src="qrCode" alt="" />
          </div>
          <div style="margin-top: 12%">
            <p class="mediana-light text-center">Código de Reserva</p>
            <p class="grande">{{ reservation.reserve_code }}</p>
          </div>

          <div class="contenedor">
            <!-- Detalles de Recogida -->
            <div class="fila">
              <p class="pequeña columna text-right">Fecha recogida:</p>
              <p class="columna grande-light">
                {{ reservation.pickup_date }}
              </p>
            </div>
            <div class="fila">
              <p class="pequeña columna text-right">Hora recogida:</p>
              <p class="columna grande-light">
                {{ reservation.pickup_hour }}
              </p>
            </div>
            <div class="fila">
              <p class="pequeña columna text-right">Lugar recogida:</p>
              <p class="columna grande-light">
                {{ reservation.pickup_city }}
              </p>
            </div>
            <div class="fila">
              <p class="columna"></p>
              <div class="columna">
                <p class="pequeña">
                  {{ reservation.pickup_branch }}
                </p>
                <div style="width: 37%">
                  <img class="img-fluid" :src="localiza_image_url" alt="agencia" />
                </div>
              </div>
            </div>
          </div>

          <div class="contenedor" style="margin-top: 5%">
            <!-- Detalles de Retorno -->
            <div class="fila">
              <p class="pequeña columna text-right">Fecha retorno:</p>
              <p class="columna grande-light">
                {{ reservation.return_date }}
              </p>
            </div>
            <div class="fila">
              <p class="pequeña columna text-right">Hora retorno:</p>
              <p class="columna grande-light">
                {{ reservation.return_hour }}
              </p>
            </div>
            <div class="fila">
              <p class="pequeña columna text-right">Lugar retorno:</p>
              <p class="columna grande-light">
                {{ reservation.return_city }}
              </p>
            </div>
            <div class="fila">
              <p class="pequeña columna text-right"></p>
              <p class="pequeña columna">
                {{ reservation.return_branch }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- AQUI CAMBIAN LOS PRECIOS DE LA RESERVA -->
      <div class="aspect-ratio-box-inner-2">
        <div class="container-white">
          <p class="grande-light text-center" style="margin-top: 3%">
            Datos del Vehículo
          </p>
          <div style="display: flex; justify-content: center; align-items: center">
            <div style="width: 35%">
              <img
                class="img-fluid"
                :src="reservation.category_image"
                :alt="reservation.category_name"
              />
            </div>
            <div style="display: flex; flex-direction: column">
              <p class="mediana">
                {{ reservation.category_name }}
              </p>
              <p class="mediana">
                {{ reservation.category_category }}
              </p>
              <p class="pequeña-light">*Suzuki Presso o Similar*</p>
            </div>
          </div>
          <p class="grande-light text-center">Datos Financieros</p>
          <div
            class="contenedor"
            style="
              margin-top: 5%;
              margin-left: 15%;
              margin-right: 15%;
              width: auto !important;
            "
          >
            <!-- DETALLES DE TARIFAS -->
            <div class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">
                Tarifa con Dto ({{ reservation.discount_percentage }}):
              </p>
              <p class="pequeña text-right columna">
                {{ reservation.daily_base_fee }}
              </p>
            </div>
            <div class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">
                Tarifa por ({{ reservation.selected_days }}) días:
              </p>
              <p class="pequeña text-right columna">
                {{ reservation.base_fee }}
              </p>
            </div>
            <div v-if="reservation.extra_hours" class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">
                + Horas Extras ({{ reservation.extra_hours }}):
              </p>
              <p class="pequeña text-right columna">
                {{ reservation.extra_hours_price }}
              </p>
            </div>
            <div v-if="reservation.return_fee" class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">+ Retorno Otra Sede:</p>
              <p class="pequeña text-right columna">
                {{ reservation.return_fee }}
              </p>
            </div>
            <div class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">Subtotal:</p>
              <p class="pequeña text-right columna">
                {{ reservation.subtotal_fee }}
              </p>
            </div>
            <div class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">+ Tasa Admin (10%):</p>
              <p class="pequeña text-right columna">
                {{ reservation.tax_fee }}
              </p>
            </div>
            <div class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">+ Impuesto 19%:</p>
              <p class="pequeña text-right columna">
                {{ reservation.iva_fee }}
              </p>
            </div>
            <div class="fila" style="margin-bottom: 1%">
              <p class="pequeña columna">Total a pagar:</p>
              <p class="pequeña text-right columna">
                {{ reservation.total_fee }}
              </p>
            </div>

            <!-- OTROS QUE SE INCLUYEN SEGUN SEA EL CASO -->
            <div style="margin-top: 6%">
              <p class="pequeña">
                <strong>El valor Incluye:</strong>
              </p>
              <p class="pequeña">
                {{ reservation.included_fees }}
              </p>
            </div>
            <div style="margin-top: 6%">
              <p class="pequeña">
                <strong>Método de Pago:</strong>
              </p>
              <p class="pequeña">Tarjeta de Crédito en Sede</p>
            </div>
          </div>
        </div>
      </div>

      <!-- AQUI CAMBIAN LOS DATOS DEL ARRENDADOR -->
      <div class="aspect-ratio-box-inner-3">
        <div class="container-white">
          <p class="grande-light text-center" style="margin-top: 3%">
            Datos del Arrendador
          </p>
          <div
            style="
              display: flex;
              justify-content: center;
              align-items: center;
              margin-top: 3%;
            "
          >
            <div style="width: 20%; margin-right: 10px">
              <img
                class="img-fluid"
                style="border-radius: 50%"
                :src="user_image_url"
                :alt="`usuario ` + reservation.fullname"
              />
            </div>

            <div style="display: flex; flex-direction: column">
              <!-- NOMBRE DEL ARRENDADOR -->
              <p class="mediana">{{ reservation.fullname }}</p>
              <!-- CEDULA DEL ARRENDADOR -->
              <p class="mediana">
                {{ reservation.identification_type }}
                {{ reservation.identification }}
              </p>
              <p class="pequeña-light">Único Reservante Autorizado</p>
            </div>
          </div>
          <div style="margin-left: 7%; margin-right: 7%; margin-top: 4%">
            <p class="pequeña text-justify">
              Los descuentos adquiridos en esta reserva son Intransferibles.
            </p>
            <br />
            <p class="pequeña text-justify">
              Solicitar cambios puede afectar los descuentos adquiridos, verifique que la
              Información sea correcta o de lo contrario solicite cuanto antes una nueva
              reserva.
            </p>
            <br />
            <p class="pequeña text-justify">
              Presentese en el lugar de recogida 30 minutos antes de la hora programada
              con los siguientes documentos:
            </p>
            <br />
            <p class="pequeña">
              1) Tarjeta de Crédito
              <br />
              2) Cédula ó Pasaporte
              <br />
              3) Licencia de Conducción vigente
            </p>
            <br />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from "vue";

const props = defineProps({
  reservation: Object,
  localiza_image_url: String,
  user_image_url: String,
});

const layout = null;

const qrCode = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${props.reservation.reserve_code}`;

function adjustFontSize() {
  const config1 = [
    { class: ".grande", minFontSize: 5, maxFontSize: 35, divisor: 11 },
    {
      class: ".grande-light",
      minFontSize: 2,
      maxFontSize: 32,
      divisor: 14,
    },
    { class: ".mediana", minFontSize: 2, maxFontSize: 30, divisor: 18 },
    {
      class: ".mediana-light",
      minFontSize: 2,
      maxFontSize: 25,
      divisor: 15,
    },
    { class: ".pequeña", minFontSize: 1, maxFontSize: 20, divisor: 23 },
    {
      class: ".pequeña-light",
      minFontSize: 1,
      maxFontSize: 20,
      divisor: 25,
    },
  ];

  const config2 = [
    { class: ".grande", minFontSize: 5, maxFontSize: 35, divisor: 11 },
    {
      class: ".grande-light",
      minFontSize: 2,
      maxFontSize: 30,
      divisor: 15,
    },
    { class: ".mediana", minFontSize: 2, maxFontSize: 25, divisor: 19 },
    {
      class: ".mediana-light",
      minFontSize: 2,
      maxFontSize: 20,
      divisor: 1,
    },
    { class: ".pequeña", minFontSize: 1, maxFontSize: 20, divisor: 24 },
    {
      class: ".pequeña-light",
      minFontSize: 1,
      maxFontSize: 20,
      divisor: 26,
    },
  ];

  const config3 = [
    { class: ".grande", minFontSize: 5, maxFontSize: 35, divisor: 11 },
    {
      class: ".grande-light",
      minFontSize: 2,
      maxFontSize: 30,
      divisor: 15,
    },
    { class: ".mediana", minFontSize: 2, maxFontSize: 25, divisor: 19 },
    {
      class: ".mediana-light",
      minFontSize: 2,
      maxFontSize: 20,
      divisor: 1,
    },
    { class: ".pequeña", minFontSize: 1, maxFontSize: 19, divisor: 23 },
    {
      class: ".pequeña-light",
      minFontSize: 1,
      maxFontSize: 20,
      divisor: 29,
    },
    {
      class: ".pequeña-pequeña",
      minFontSize: 1,
      maxFontSize: 15,
      divisor: 29,
    },
  ];

  adjustContainerFontSize(".aspect-ratio-box-inner", config1);
  adjustContainerFontSize(".aspect-ratio-box-inner-2", config2);
  adjustContainerFontSize(".aspect-ratio-box-inner-3", config3);
}

function adjustContainerFontSize(containerSelector, config) {
  const parent = document.querySelector(containerSelector);
  const parentWidth = parent.clientWidth;

  config.forEach((element) => {
    const items = parent.querySelectorAll(element.class);
    let fontSize = Math.min(
      Math.max(parentWidth / element.divisor, element.minFontSize),
      element.maxFontSize
    );

    // Aumentar tamaño de fuente proporcionalmente para pantallas grandes
    if (parentWidth > 720) {
      fontSize = fontSize * 1.1;
    } else if (parentWidth < 700) {
      fontSize = fontSize * 0.9;
    }

    items.forEach((item) => {
      item.style.fontSize = `${fontSize}px`;
    });
  });

  adjustImages(containerSelector);
}

function adjustImages(containerSelector) {
  const parent = document.querySelector(containerSelector);
  const images = parent.querySelectorAll("img.img-fluid");
  const parentWidth = parent.clientWidth;

  images.forEach((img) => {
    const originalWidth = img.getAttribute("width");
    img.style.width = `${parentWidth * 0.5}px`;
    img.style.height = "auto";
  });
}

onMounted(() => {
  // Llama a la función al cargar la página y al redimensionar la ventana
  window.addEventListener("resize", adjustFontSize);
  adjustFontSize();
  // document.addEventListener("DOMContentLoaded", adjustFontSize);
});
</script>

<style lang="css">
.body {
  margin: 0;
}

p {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.parent-container {
  display: flex;
  gap: 1%;
  position: relative;
}

.aspect-ratio-box-inner {
  display: flex;
  flex-direction: column;
  width: 100%;
  aspect-ratio: 14 / 19 !important;
  background-color: #1266fd;
  overflow: hidden;
}

.aspect-ratio-box-inner-2 {
  display: flex;
  flex-direction: column;
  width: 100%;
  aspect-ratio: 14 / 19 !important;
  background-color: #1266fd;
  overflow: hidden;
}

.aspect-ratio-box-inner-3 {
  display: flex;
  flex-direction: column;
  width: 100%;
  aspect-ratio: 14 / 19 !important;
  background-color: #1266fd;
  overflow: hidden;
}

.grande {
  font-weight: 900;
}

.grande-light {
  font-weight: 800;
}

.mediana {
  font-weight: 500;
}

.mediana-light {
  font-weight: 500;
}

.pequeña {
}

.pequeña-light {
  font-weight: 200;
}

.img-fluid {
  max-width: 100%;
  height: auto;
}

.container-white {
  height: 100%;
  width: 100%;
  background-color: #ffffff;
  margin-top: 5%;
  margin-bottom: 5%;
}

.text-center {
  text-align: center;
}

.text-right {
  text-align: right;
}

.contenedor {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin-right: 11%;
}

.fila {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: space-between;
}

.columna {
  width: 48%;
}

.columna-1 {
  width: 38%;
}

.ultimo {
  display: flex;
  flex-direction: column;
  width: auto;
}

.text-justify {
  text-align: justify;
}
</style>
