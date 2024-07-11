import { ref, Ref, nextTick } from 'vue'


export const componentsShow : Ref<boolean> = ref(true);

export const forceReload = async () => 
{
    componentsShow.value = false;

    await nextTick();
    
    componentsShow.value = true;
}