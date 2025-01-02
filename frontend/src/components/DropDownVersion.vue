<script setup lang="ts">
	import "./scss/DropDown.scss";
	import { ref, Ref, ComputedRef, defineProps, defineEmits, onUnmounted } from "vue";

	import { JsonData } from "../ts/interfaces/JsonData";

	import { LangDataHandler } from "../ts/handlers/LangDataHandler";
	import langsData from "./locales/DropDownVersion.json";

	import { ArticleVersion } from "../ts/interfaces/Article";

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("DropDownVersion", langsData).langData;

	const props = defineProps(
    {
        versions: 
        {
            type: Array<ArticleVersion>,
            default: [],
        }
    });

	const emits = defineEmits<{
		(e: 'update', payload: number): void;
	}>();

	const selected : Ref<ArticleVersion> = ref(props.versions[0]);

	const open : Ref<boolean> = ref(false);

	onUnmounted(() =>
	{
		LangDataHandler.destroyLangDataHandler('DropDownVersion');
	});
</script>

<template>
    <div class="customSelect" @blur="open = false">
        <div class="customSelect__selected" :class="{ open: open }" @click="open = !open">
            {{ langData['version'] as string + selected.version_id }}
			<span class="customSelect__selected__img"></span>
        </div>
        <div class="customSelect__items" :class="{ selectHide: !open }">
			<div 
				v-for="(version, index) in versions" :key="index"
				@click="
					selected = version;
					open = false;
					
					emits('update', index);">
				{{ langData['version'] as string + version.version_id }}
			</div>
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/DropDown.scss"></style>