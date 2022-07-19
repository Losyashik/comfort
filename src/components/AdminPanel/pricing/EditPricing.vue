<template>
  <div id="pricing">
    <nav>
      <ul>
        <li>
          <router-link :to="{ name: 'Providers', active: true }"
            >Поставщики</router-link
          >
        </li>
        <li>
          <router-link :to="{ name: 'AddPrice' }"
            >Добавление закупочных цен</router-link
          >
        </li>
        <li>
          <router-link :to="{ name: 'AddAllowances' }">Наценка</router-link>
        </li>
      </ul>
    </nav>
    <router-view></router-view>
  </div>
</template>
<style
  scoped
  lang="scss"
  src="./../../../assets/styles/admin/pricingNav.scss"
></style>
