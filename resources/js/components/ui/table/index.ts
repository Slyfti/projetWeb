import { defineComponent, h } from 'vue';

export const Table = defineComponent({
    name: 'Table',
    setup(_, { slots }) {
        return () => h('table', { class: 'w-full caption-bottom text-sm' }, slots.default?.());
    },
});

export const TableHeader = defineComponent({
    name: 'TableHeader',
    setup(_, { slots }) {
        return () => h('thead', { class: '[&_tr]:border-b' }, slots.default?.());
    },
});

export const TableBody = defineComponent({
    name: 'TableBody',
    setup(_, { slots }) {
        return () => h('tbody', { class: '[&_tr:last-child]:border-0' }, slots.default?.());
    },
});

export const TableRow = defineComponent({
    name: 'TableRow',
    setup(_, { slots }) {
        return () => h('tr', { class: 'border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted' }, slots.default?.());
    },
});

export const TableHead = defineComponent({
    name: 'TableHead',
    setup(_, { slots }) {
        return () => h('th', { class: 'h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0' }, slots.default?.());
    },
});

export const TableCell = defineComponent({
    name: 'TableCell',
    setup(_, { slots }) {
        return () => h('td', { class: 'p-4 align-middle [&:has([role=checkbox])]:pr-0' }, slots.default?.());
    },
}); 