<script setup lang="ts">
	import "./scss/DropDown.scss";
	import { ref, ComputedRef, defineProps, defineEmits } from "vue";

	import { JsonData } from "../ts/interfaces/JsonData";

	import { LangDataHandler } from "../ts/handlers/LangDataHandler";
	import langsData from "./locales/DropDownVersion.json";

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("DropDownVersion", langsData).langData;

	const props = defineProps(["versions"]);
	const emits = defineEmits(["input"]);

	const selected = ref(props.versions[0]);

	const open = ref(false);
</script>

<template>
    <div class="customSelect" @blur="open = false">
        <div class="customSelect__selected" :class="{ open: open }" @click="open = !open">
            {{ langData['version'] + selected.version_id }}
			<span class="customSelect__selected__img"></span>
        </div>
        <div class="customSelect__items" :class="{ selectHide: !open }">
			<div 
				v-for="(version, index) in versions" :key="index"
				@click="
					selected = version;
					open = false;
					
					$emit('input', index);">
				{{ langData['version'] + version.version_id }}
			</div>
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/DropDown.scss"></style>