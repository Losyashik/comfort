<template>
  <div class="loader">
    <div class="clock-loader"></div>
  </div>
</template>
<style lang="scss" scoped>
.loader {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  margin: 0;
  .clock-loader {
    --clock-color: #000;
    --clock-width: 4rem;
    --clock-radius: calc(5vw / 2);
    --clock-minute-length: calc(5vw * 0.4);
    --clock-hour-length: calc(5vw * 0.2);
    --clock-thickness: 0.2rem;

    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 5vw;
    height: 5vw;
    border: 3px solid var(--clock-color);
    border-radius: 50%;

    &::before,
    &::after {
      position: absolute;
      content: "";
      top: calc(var(--clock-radius) * 0.25);
      width: var(--clock-thickness);
      background: var(--clock-color);
      border-radius: 10px;
      transform-origin: center calc(100% - calc(var(--clock-thickness) / 2));
      animation: spin infinite linear;
    }

    &::before {
      height: var(--clock-minute-length);
      animation-duration: 2s;
    }

    &::after {
      top: calc(var(--clock-radius) * 0.25 + var(--clock-hour-length));
      height: var(--clock-hour-length);
      animation-duration: 15s;
    }
  }

  @keyframes spin {
    to {
      transform: rotate(1turn);
    }
  }
}
</style>
