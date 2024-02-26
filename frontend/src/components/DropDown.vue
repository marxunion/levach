<script lang="ts">
import ".scss/DropDown.vue"
export default {
  props: 
  {
    options: 
    {
      type: Array,
      required: true,
    },
    default: 
    {
      type: String,
      required: false,
      default: null,
    },
    tabindex: 
    {
      type: Number,
      required: false,
      default: 0,
    },
  },
  data() 
  {
    return {
        selected: 
            this.default
            ? this.default
            : this.options.length > 0
            ? this.options[0]
            : null,
        open: false,
    };
  },
  name: "DropDown",
  mounted() {
    this.$emit("input", this.selected);
  },
};
</script>

<template>
    <div class="custom-select" :tabindex="tabindex" @blur="open = false">
        <div class="selected" :class="{ open: open }" @click="open = !open">
            {{ selected }}
        </div>
        <div class="items" :class="{ selectHide: !open }">
            <div
            v-for="(option, i) of options"
                :key="i"
                @click="
                    selected = option;
                    open = false;
                    $emit('input', option);">
            {{ option }}
            </div>
        </div>
    </div>
</template>