<template>
    <header>
        <img src="@/assets/common/brazil.png"/>
        <h6>GAP - Logística e Transportes LTDA. <span style="color: yellow; font-weight: 600;">(AMBIENTE DE TESTES)</span></h6>
        <button @click="clean" v-show="this.$route.path !== endpoints.routes.LOGIN">
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
        endpoints
    }),
    methods: {
        clean() {
            localStorage.clear();
            this.$store.dispatch("clearAll");
            router.push(endpoints.routes.LOGIN);
        }
    }
};
</script>
<style scoped>

header {
  padding: 10px;
  background-color: var(--green-4);
  color: white;
  display: grid;
  grid-template-columns: repeat(24, 1fr); 
  grid-template-areas: 
    "logo title title title title title title title title title title title title . . . . . . . . . . button";
  position: fixed; 
  align-items: center;
  left: 0;
  right: 0;
  top: 0;
  z-index: 1000;
}

header img {
  height: 62px;
}

button {
    background-color: transparent;
    border: none;
    outline: none;
    cursor: pointer;
    justify-self: end;
    grid-area: button;
}

img {
    grid-area: logo;
    padding-right: 15px;
}

h6 {
    grid-area: title;
}
</style>