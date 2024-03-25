<template>
  <div
    @click="close()"
    class="modal"
    :class="{ active: modal.active, error: modal.error }"
  >
    <div class="text">
      {{ modal.text }}
    </div>
  </div>
</template>
<script>
import { mapGetters, mapMutations } from "vuex";

export default {
  methods: {
    ...mapMutations({ close: "closeModalWindow" }),
  },
  computed: {
    ...mapGetters({ modal: "GET_MODAL_WINDOW_DATA" }),
  },
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
