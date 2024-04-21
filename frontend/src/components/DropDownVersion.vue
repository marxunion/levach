<script setup lang="ts">
import "./scss/DropDown.scss";
import { ref, defineProps, defineEmits } from "vue";

import { LangDataHandler } from "./../ts/LangDataHandler";
import langsData from "./locales/DropDownVersion.json";

const langData = ref(LangDataHandler.initLangDataHandler("DropDownVersion", langsData).langData);

const props = defineProps(["maxVersion"]);
const emits = defineEmits(["input"]);

const selected = ref(props.maxVersion);

const open = ref(false);

</script>

<template>
    <div class="customSelect" @blur="open = false">
        <div class="customSelect__selected" :class="{ open: open }" @click="open = !open">
            {{ langData['version'] + selected }}
			<span class="customSelect__selected__img"></span>
        </div>
        <div class="customSelect__items" :class="{ selectHide: !open }">
			<div 
				v-for="option in maxVersion"
				@click="
					selected = option;
					open = false;
					$emit('input', option as number);">
				{{ langData['version'] + option }}
			</div>
        </div>
    </div>
</template>


<style lang="scss" scoped src="./scss/DropDown.scss"></style>