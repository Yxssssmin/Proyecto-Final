<template>
    <div class="modal-overlay">
      <div class="modal">
        <img class="check" src="/images/check-icon.png" alt="" />
        <h6>¡Guardado!</h6>
        <p>Datos guardados exitosamente</p>
        <button>Go Home</button>
      </div>
      <div class="close" @click="$emit('close-modal')">
        <img class="close-img" src="/images/close.svg" alt="" />
      </div>
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            showModal: false,
        }
    },
}
</script>
