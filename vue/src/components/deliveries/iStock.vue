<template>
    <li class="container-external">
        <div class="container-internal">
            <iInternalStock
                :title="name"
                icon="tags-solid"
                :value="formattedName"
            />
            <iInternalStock
                icon="box-solid"
                :value="typeName"
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
            />
            <iSvg
                :src="require(`@/assets/icons/truck-front-solid.svg`)"
                width="16"
                height="16"
                :fill="finished ? 'var(--green-2)' : 'var(--yellow-1)'"
                :title="finished ? 'Finalizada' : 'Aberta'"
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
        "finished"
    ],
    components: {
        iInternalStock,
    },
    computed:{
        formattedName() {
            const limit = 8;
            return this.name.length > limit ? this.name.slice(0, limit) + "..." : this.name;
        },

        typeName() {
            return this.$store.state.stockTypeMod.stockTypes.find(({ id }) => id === this.type).name;
        }
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
    background-color: rgba(0, 0, 0, 0.5);
    position: relative;
    overflow: hidden;
    border: 5px solid;
    user-select: none;
    backdrop-filter: blur(6px);
}

.container-internal {
    display: grid;
    grid-auto-rows: 50px;
    grid-template-columns: 20% 18% 15% 15% 20% 2%;
    justify-content: center;
    align-items: center;
    grid-gap: 10px;
}
</style>