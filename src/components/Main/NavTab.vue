<template>
  <div>
    <span class="text">
      <template v-if="data.no != 'new'">Заявка №{{ data.no }}</template
      ><template v-else>Новая заявка</template></span
    ><span class="close" @click.stop="$emit('close')"
      ><img src="./../../assets/images/interface/close.png" alt=""
    /></span>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  emits: ["close"],
};
</script>

<style></style>
