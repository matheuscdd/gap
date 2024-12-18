<template>
    <li class="container-external" :style="{backgroundColor: color.colora}">
        <div class="container-internal">
            <iInternal icon="file-contract-solid" :value="id" />
            <iInternal icon="calendar-solid" :value="deliveryDate" />
            <iInternal
                icon="dolly-solid"
                :value="unloaded === 'client' ? 'Cliente' : 'Transportadora'"
            />
            <iInternal icon="user-solid" :value="driver" />
            <iInternal icon="cart-flatbed-solid" :value="unloadingCost" />
        </div>
    </li>
</template>
<script>
import { randomColor } from "@/common/utils";
import iInternal from "./iInternal.vue";

export default {
    data: () => ({
        color: randomColor(0.2)
    }),
    props: [
        "id",
        "deliveryDate",
        "unloaded",
        "driver",
        "unloadingCost",
        "stocks",
    ],
    components: {
        iInternal,
    },
};
</script>
<style scoped>
.container-external {
    padding: 0 20px;
    height: 50px;
    border-radius: var(--radius-2);
    color: #fff;
    position: relative;
    overflow: hidden;
    border: 5px solid;
    user-select: none;
    backdrop-filter: blur(6px) brightness(43%);
    margin-bottom: 15px;
}

.container-internal {
    display: grid;
    grid-auto-rows: 50px;
    grid-template-columns: 20% 20% 20% 20% 10%;
    justify-content: center;
    align-items: center;
    grid-gap: 10px;
}
</style>
