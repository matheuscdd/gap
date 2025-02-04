<template>
  <div v-show="data.length" class="container">
    <Plotly :data="data" :layout="{ title: this.name }" :display-mode-bar="false" @plotly_legendclick="handleLegendClick"></Plotly>
    <h6>Totais</h6>
    <ul class="cards">
      <li
      v-for="(el, i) in data"
        class="card"
        :style="{borderColor: el.line.color, color: el.line.color}"
        :key="el.name"
        v-show="!hiddenTotals.has(i)"
      >
        <span>{{ el.name }}</span>
        <small>{{ sum(el.y) }}</small>
      </li>
    </ul>
  </div>
</template>

<script>
import Plotly from "@aurium/vue-plotly";
 
 export default {
   props: ["data", "name", "indexPositive"],
   components: {
     Plotly
   },
   data: () => ({
      hiddenTotals: new Set(),
   }),
   methods: {
    handleLegendClick(e) {
      const traceIndex = e.curveNumber;
      const trace = e.fullData[traceIndex];
      const visible = trace.visible !== true;
      this.hiddenTotals[visible ? "delete" : "add"](traceIndex);
    },
    sum(arr) {
      return arr.reduce((a, c) => a + c , 0).toLocaleString("pt-br", {style: "currency", currency: "BRL"});
    }
   }
};
</script>
<style scoped>
h6 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 18px;
  color: var(--gray-1);
}

.container {
  background-color: #FFF;
  border-radius: 5px;
}

.cards {
  display: grid;
  grid-auto-rows: 275px;
  grid-auto-flow: column;
  justify-items: center;
}

.card {
  border-radius: 100%;
  height: 200px;
  width: 200px;
  border: 10px solid;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.revenue {
  border-color: var(--green-2);
  color: var(--green-2);
}

.cost {
  border-color: var(--red-1);
  color: var(--red-1);
}
</style>