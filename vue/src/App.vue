<template>
  <main>
    <iHeader/>
    <section>
      <iAside/>
      <div>
        <iChoice ref="iChoice"/>
        <RouterView/>
      </div>
    </section>
    <footer>
      Copyright © {{ CARRIER_PARCIAL_NAME }} {{ new Date().getFullYear() }}
    </footer>
  </main>
</template>
<script>
import iAside from "./components/common/iAside.vue";
import iChoice from "./components/common/iChoice.vue";
import iHeader from "./components/common/iHeader.vue";

export default {
  name: "App",
  data: () => ({
    CARRIER_PARCIAL_NAME: process.env.VUE_APP_CARRIER_PARCIAL_NAME,
  }),
  components: {
    iAside,
    iHeader,
    iChoice
  },
  beforeCreate() {
      if (!localStorage.getItem("token")) return;
      this.$store.dispatch("userMod/storeLogged");
  },  
  mounted() {
    this.$store.commit("setChoice", this.$refs.iChoice);
  }
};
</script>

<style scoped>
main {
  margin-top: 80px;
  padding-bottom: 100px;
}

footer {
  background-color: var(--green-3);
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  width: 100vw;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 15px 0;
  position: fixed; 
  z-index: 1000;
}

section {
  display: flex;
  gap: 0.5vw;
  margin-right: 2vw;
}

div {
  width: 100%;
}
</style>
