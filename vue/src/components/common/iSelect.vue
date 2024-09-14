<template>
    {{ name }}
    <div :class="[$style.external]">
        <label :class="$style.intermediary" :for="id">
            <legend>{{ label }}</legend>
            <div :class="$style.internal">
                <iSvg 
                    :src="require(`@/assets/icons/${icon}.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <select :id="id" :value="modelValue" @change="$emit('update:modelValue', $event.target.value)">
                    <option
                        v-for="opt in opts"
                        :key="opt.value"
                        :value="opt.value"
                    >
                        {{ opt.text }}
                    </option>
                </select>
            </div>
        </label>
    </div>

</template>

<script>
import { getUUID, sleep } from "@/common/utils";
import styles from "@/global/bInput.module.css";

export default {
    data: () => ({
        id: getUUID(),

    }),
    props: [
        "icon",
        "opts",
        "label",
        "modelValue"
    ],
    computed: {
        $style() {
            return styles;
        },
    },  
    methods: {
        
    },
};
</script>

<style module>
@import "@/global/bInput.module.css";
</style>