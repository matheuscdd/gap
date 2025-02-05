<template>
    <div :class="[$style.external, {[$style.invalid]: empty || errors.length}]">
        <label :class="$style.intermediary" :for="id">
            <legend>{{ label }}</legend>
            <div :class="$style.internal">
                <iSvg 
                    :src="require(`@/assets/icons/${icon}.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <span
                    v-if="type === 'file'"
                    :class="$style.filename"
                >
                    {{ filename }}
                </span>
                <input 
                    v-show="type !== 'file'"
                    :disabled="!!readonly"
                    :id="id" 
                    :placeholder="placeholder" 
                    :type="kind" 
                    :value="modelValue"
                    :maxlength="maxlength"
                    :accept="accept"
                    @blur="outFocus" 
                    @focusin="updateValue"
                    @input="updateValue"
                />
                <button 
                    type="button"
                    v-show="type === 'password'" 
                    @click="showPassword = !showPassword"
                >
                    <iSvg 
                        :src="require(`@/assets/icons/${showPassword ? 'eye-slash-solid' : 'eye-solid'}.svg`)"
                        width="16" 
                        height="16"
                        fill="currentColor"
                    />
                </button>
            </div>
        </label>
        
        <ul v-show="errors.length" :class="$style.errors">
            <li v-for="el in errors" :key="el + getUUID()">
                <legend>{{ el }}</legend>
            </li>
        </ul>
    </div>
</template>

<script>
import { getUUID, renderFileReader } from "@/common/utils";
import styles from "@/global/bInput.module.css";

export default {
    data: () => ({
        id: getUUID(),
        empty: false,
        showPassword: false,
        localFilename: "",
    }),
    props: [
        "placeholder", 
        "icon", 
        "label", 
        "name", 
        "accept",
        "modelValue", 
        "type",
        "errors",
        "maxlength",
        "readonly",
        "nullable",
        "savedFilename",
    ],
    watch: {
        modelValue(value) {
            this.empty = !String(value).length && !this.nullable;
        }
    },
    computed: {
        $style() {
            return styles;
        },

        filename() {
            return (this.localFilename || this.savedFilename || this.placeholder).slice(0, 20) + "...";
        },
 
        kind() {
            if (this.type === "password" ) {
                return this.showPassword ? "text" : "password";
            } else if (this.type === "number") {
                return "text";
            }
            return this.type;
        }

    },
    methods: {   
        outFocus() {
            this.empty = !String(this.modelValue).length && !this.nullable;
            this.$emit("validate", this.name);
        },
        async updateValue(e) {
            if (this.type === "file") {
                const file = e.target.files[0];
                const b64 = await this.renderFileReader(file);
                if (file) this.localFilename = file.name;
                return this.$emit("loadFile", b64);
            }
            let val = e.target.value;
            if (this.type === "number") {
                val = e.target.value.replace(/[^0-9.]/g, "").replace(/\.(?=.*\.)/g, "");
                if (e.target.value.length && !["CEP", "CPF", "CNPJ"].includes(this.name)) val = Number(val);
            }
            this.$emit("update:modelValue", val);
        },
        renderFileReader,
        getUUID,
    }
};

</script>

<style module>
@import "@/global/bInput.module.css";
</style>