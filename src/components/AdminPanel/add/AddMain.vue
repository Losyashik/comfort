<template>
  <div>
    <div class="data">
      <div class="recently">
        <h2>Недавно использованные</h2>
        <div class="flex">
          <div class="button">
            <router-link
              :to="{
                name: 'Form',
                params: { product: 'linoleum', type: 'linoleum' },
              }"
              >Линолеум</router-link
            >
          </div>
          <div class="button">
            <router-link
              :to="{
                name: 'Form',
                params: { product: 'plinth', type: 'plinth' },
              }"
              >Плинтуса</router-link
            >
          </div>
          <div class="button">
            <router-link
              :to="{
                name: 'Form',
                params: { product: 'plinth', type: 'collection' },
              }"
              >Коллекции плинтусов</router-link
            >
          </div>
          <div class="button">
            <router-link
              :to="{
                name: 'Form',
                params: { product: 'linoleum', type: 'collection' },
              }"
              >Коллекции линолеума</router-link
            >
          </div>
          <div class="button">
            <router-link
              :to="{
                name: 'Form',
                params: { product: 'order', type: 'cites' },
              }"
              >Город</router-link
            >
          </div>
        </div>
      </div>
      <div class="interface">
        <h2>Все элементы</h2>
        <div class="flex">
          <div class="block">
            <h4>Линолеум</h4>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'linoleum', type: 'linoleum' },
                }"
                >Линолиум</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'linoleum', type: 'collection' },
                }"
                >Коллекция</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'linoleum', type: 'maker' },
                }"
                >Производитель</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'linoleum', type: 'class' },
                }"
                >Класс</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'linoleum', type: 'base' },
                }"
                >Основа</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'linoleum', type: 'destination' },
                }"
                >Предназначение</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'linoleum', type: 'type' },
                }"
                >Тип</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'recommended', type: 'recommended' },
                }"
                >Рек. плинтуса</router-link
              >
            </div>
          </div>
          <div class="block">
            <h4>Плинтуса</h4>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'plinth', type: 'plinth' },
                }"
                >Плинтус</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'plinth', type: 'collection' },
                }"
                >Коллекция</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'plinth', type: 'maker' },
                }"
                >Производитель</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'plinth', type: 'accessories' },
                }"
                >Фурнитура</router-link
              >
            </div>
          </div>
          <div class="block">
            <h4>Заявки</h4>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'order', type: 'connection' },
                }"
                >Тип связи</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'order', type: 'cites' },
                }"
                >Город</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'order', type: 'status' },
                }"
                >Статус</router-link
              >
            </div>
          </div>
          <div class="block">
            <h4>Пороги</h4>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'doorstep', type: 'doorstep' },
                }"
                >Пороги</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'doorstep', type: 'color' },
                }"
                >Цвет</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'doorstep', type: 'maker' },
                }"
                >Производитель</router-link
              >
            </div>
            <div class="button">
              <router-link
                :to="{
                  name: 'Form',
                  params: { product: 'doorstep', type: 'size' },
                }"
                >Размер</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped lang="scss" src="./../../../assets/styles/admin/add.scss"></style>
