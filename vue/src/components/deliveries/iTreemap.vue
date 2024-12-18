<template>
  <div v-show="data.length">
    <Plotly :data="data" :layout="layout" :display-mode-bar="false"></Plotly>
  </div>
</template>

<script>
import Plotly from "@aurium/vue-plotly";
 
 export default {
   props: ["data"],
   components: {
     Plotly
   },
   data:() => ({
     layout:{
       background: "transparent",
       paper_bgcolor: "rgba(0, 0, 0, 0)",
       margin: {
        l: 0,
        r: 0,
        t: 25,
        b: 0,
       },
       treemapcolorway: Array(10000).fill(["#388E3C", "#A1C164", "#FFF59D"]).flat()
     }
   })
};
</script>