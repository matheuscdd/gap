<template>
    <div></div>
<Plotly :data="data" :layout="layout" :display-mode-bar="false"></Plotly>
</template>

<script>
import Plotly from "@aurium/vue-plotly";
 
 export default {
   components: {
     Plotly
   },
   data:() => ({
     data:[{
      type: "treemap",
      labels: ["Eve", "Cain", "Seth", "Enos", "Noam", "Abel", "Awan", "Enoch", "Azura"],
      parents: ["", "Eve", "Eve", "Seth", "Seth", "Eve", "Eve", "Awan", "Eve" ]}],
     layout:{
       title: "My graph"
     }
   })
};
</script>