<template>
    <header>
        <img class="logo" src="@/assets/common/brazil-white.png"/>
        <h6>{{ CARRIER_COMPLETE_NAME }} <span style="color: yellow; font-weight: 600;">(AMBIENTE DE TESTES)</span></h6>
        <div class="container-img photo" v-show="isLogged">
            <img :src="$store.state.userMod.logged.photo || require('@/assets/common/user_placeholder.png')" alt="Foto de um usuário"/>
        </div>
        <span v-show="isLogged">{{ $store.state.userMod.logged.name }}</span>
        <button @click="clean" v-show="isLogged">
            <iSvg 
                :src="require('@/assets/icons/right-from-bracket-solid.svg')"
                width="16" 
                height="16"
                fill="white"
            />
        </button>
    </header>      
</template>
<script>
import { endpoints } from "@/common/consts";
import router from "@/router";

export default {
    data: () => ({
        endpoints,
        CARRIER_COMPLETE_NAME: process.env.VUE_APP_CARRIER_COMPLETE_NAME,
    }),
    computed: {
        isLogged() {
            return ![endpoints.names.LOGIN, endpoints.names.USER_PASSWORD_LOST, endpoints.names.USER_PASSWORD_RESET ].includes(this.$route.name);
        }
    },
    methods: {
        clean() {
            localStorage.clear();
            this.$store.dispatch("clearAll");
            router.push(endpoints.routes.LOGIN);
        },
    }
};
</script>
<style scoped>
header {
  padding: 10px;
  background-color: var(--green-4);
  color: white;
  display: grid;
  grid-template-columns: repeat(36, 1fr); 
  grid-template-areas: 
    "logo title title title title title title title title title title title title title title title title title title title title title title title title title title . . . . . . username photo  button";
  position: fixed; 
  align-items: center;
  left: 0;
  right: 0;
  top: 0;
  z-index: 1000;
}

.logo {
  height: 62px;
}

.container-img {
    width: 35px;
    height: 35px;
    object-fit: cover;
    border-radius: 100%;
    overflow: hidden;
    box-shadow: 0px 0px 0px 4px var(--gray-2);
}

.container-img > img {
    background-color: #e2e2e284;
    width: 45px;
    height: 45px;
    object-fit: cover;
}

button {
    background-color: transparent;
    border: none;
    outline: none;
    cursor: pointer;
    justify-self: end;
    grid-area: button;
}

span {
    grid-area: username;
}

.logo {
    grid-area: logo;
    padding-right: 15px;
}

h6 {
    grid-area: title;
}

.photo {
    grid-area: photo;
    margin-left: 20px;
}
</style>