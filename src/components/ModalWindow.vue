<template>
  <div
    v-if="obj"
    @click="$emit('close')"
    class="modal"
    :class="{ active: obj.active, error: obj.error }"
  >
    <div class="text">
      {{ obj.text }}
    </div>
  </div>
</template>
<script>
export default {
  emits: ["close"],
  props: { obj: Object },
};
</script>
<style scoped>
.modal {
  position: absolute;
  display: flex;
  justify-content: space-around;
  align-items: center;
  min-height: 6%;
  bottom: 0;
  right: 5%;
  opacity: 0;
  min-width: 20%;
  max-width: 50%;
  padding: 10px 20px;
  background-color: #46d646;
  transition: all 0.8s;
  z-index: -10;
}
.active {
  opacity: 1;
  z-index: 100;
}
.modal.error {
  background-color: #f85a5a;
}
</style>
