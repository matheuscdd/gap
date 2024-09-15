<template>
  <main>
    <header>
      <img src="@/assets/common/brazil.png"/>
      <span>GAP - Logística e Transportes LTDA.</span>
    </header>
    <RouterView/>
    <footer>
      Copyright © GAP 2024
    </footer>
  </main>
</template>

<script>
export default {
  name: "App"  
};
</script>

<style scoped>
main {
  background-image: url('@/assets/common/waves.svg');
  background-position-x: center;
  background-position-y: bottom;
  background-attachment: fixed;
  background-size: 100%;
  min-height: 100vh;
  background-repeat: no-repeat;
  margin-top: 90px;
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

header {
  padding: 10px;
  background-color: var(--green-4);
  color: white;
  display: flex;
  align-items: center;
  justify-content: left;
  gap: 20px;
  position: fixed; 
  left: 0;
  right: 0;
  top: 0;
  z-index: 1000;
}

header img {
  height: 62px;
}


</style>
