<script setup lang="ts">
import "./scss/DropDown.scss";
import { ref, computed, defineProps, defineEmits } from "vue";

import { LangDataHandler } from "./../ts/LangDataHandler";
import langsData from "./locales/DropDownVersion.json";

const langData = LangDataHandler.initLangDataHandler("DropDownVersion", langsData).langData;

const props = defineProps(["maxVersion"]);
const emits = defineEmits(["input"]);

const reversedVersions = computed(() => 
{
  	const numbers = [];
  	for (let i = props.maxVersion; i >= 1; i--) 
	{
    	numbers.push(i);
  	}
	
  	return numbers;
});

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
				v-for="version in reversedVersions" :key="version"
				@click="
					selected = version;
					open = false;
					$emit('input', version as number);">
				{{ langData['version'] + version }}
			</div>
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/DropDown.scss"></style>