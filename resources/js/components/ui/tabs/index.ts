import { defineComponent, h, PropType, computed, provide, inject, ref, Ref } from 'vue';

const TabsContext = Symbol('TabsContext');

interface TabsContextValue {
  activeTab: Ref<string>;
  setActiveTab: (value: string) => void;
}

export const Tabs = defineComponent({
  name: 'Tabs',
  props: {
    modelValue: {
      type: String,
      required: true,
    },
  },
  emits: ['update:modelValue'],
  setup(props, { slots, emit }) {
    const activeTab = computed({
      get: () => props.modelValue,
      set: (value) => emit('update:modelValue', value),
    });

    provide(TabsContext, {
      activeTab,
      setActiveTab: (value: string) => {
        activeTab.value = value;
      },
    });

    return () => h('div', { class: 'tabs' }, slots.default?.());
  },
});

export const TabsList = defineComponent({
  name: 'TabsList',
  setup(_, { slots }) {
    return () => h('div', { class: 'tabs-list' }, slots.default?.());
  },
});

export const TabsTrigger = defineComponent({
  name: 'TabsTrigger',
  props: {
    value: {
      type: String,
      required: true,
    },
  },
  setup(props, { slots }) {
    const context = inject<TabsContextValue>(TabsContext);
    
    if (!context) {
      throw new Error('TabsTrigger must be used within a Tabs component');
    }

    const isActive = computed(() => context.activeTab.value === props.value);

    return () => h('button', { 
      class: 'tabs-trigger',
      'data-state': isActive.value ? 'active' : 'inactive',
      onClick: () => context.setActiveTab(props.value)
    }, slots.default?.());
  },
});

export const TabsContent = defineComponent({
  name: 'TabsContent',
  props: {
    value: {
      type: String,
      required: true,
    },
  },
  setup(props, { slots }) {
    const context = inject<TabsContextValue>(TabsContext);
    
    if (!context) {
      throw new Error('TabsContent must be used within a Tabs component');
    }

    const isActive = computed(() => context.activeTab.value === props.value);

    return () => h('div', { 
      class: 'tabs-content',
      'data-state': isActive.value ? 'active' : 'inactive',
      style: {
        display: isActive.value ? 'block' : 'none'
      }
    }, slots.default?.());
  },
}); 