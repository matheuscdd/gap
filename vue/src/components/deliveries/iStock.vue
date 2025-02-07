<template>
    <li class="container-external" :style="{backgroundColor: background || '#00000080'}">
        <div class="container-internal">
            <iInternalStock
                :title="name"
                icon="tags-solid"
                :value="formattedName"
            />
            <iInternalStock
                icon="box-solid"
                :value="$store.state.stockTypeMod.stockTypes.find(({ id }) => id === type).name"
            />
            <iInternalStock
                icon="boxes-stacked-solid"
                :value="quantity"
            />
            <iInternalStock
                icon="weight-hanging-solid"
                :value="weight"
            />
            <iInternalStock
                icon="ruler-combined-solid"
                :value="extra"
                title="Largura | Altura"
            />
        </div>
    </li>
</template>
<script>
import iInternalStock from "./iInternal.vue";

export default {
    props: [
        "name",
        "type",
        "weight",
        "quantity",
        "extra",
        "background"
    ],
    components: {
        iInternalStock,
    },
    computed:{
        formattedName() {
            const limit = 8;
            return this.name.length > limit ? this.name.slice(0, limit) + "..." : this.name;
        },
    }
};
</script>
<style scoped>
.container-external {
    flex-grow: 1;
    min-width: 600px;
    height: 50px;
    padding: 0 20px;
    border-radius: var(--radius-2);
    color: #fff;
    position: relative;
    overflow: hidden;
    border: 5px solid;
    user-select: none;
    backdrop-filter: blur(6px);
}

.container-internal {
    display: grid;
    grid-auto-rows: 50px;
    grid-template-columns: 20% 20% 15% 15% 20%;
    justify-content: center;
    align-items: center;
    grid-gap: 10px;
}
</style>