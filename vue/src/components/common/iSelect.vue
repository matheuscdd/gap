<template>
    <div :class="[$style.external]">
        <div :class="$style.intermediary" :for="id">
            <legend v-show="label">{{ label }}</legend>
            <div :class="$style.internal">
                <iSvg 
                    :src="require(`@/assets/icons/${icon}.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <select :id="id" :disabled="readonly" :value="modelValue" @change="$emit('update:modelValue', $event.target.value)">
                    <option
                        v-for="opt in opts"
                        :key="opt.id"
                        :value="opt.id"
                    >
                        {{ opt.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>

</template>

<script>
import { getUUID } from "@/common/utils";
import styles from "@/global/bInput.module.css";

export default {
    data: () => ({
        id: getUUID(),

    }),
    props: [
        "icon",
        "opts",
        "label",
        "modelValue",
        "readonly",
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