<template>
  <Link
    :href="href"
    class="flex items-center p-3 rounded-2xl transition-all duration-200 group relative overflow-hidden"
    :class="[
      active
        ? 'bg-gradient-to-l from-[#d4af37]/20 to-transparent text-[#d4af37]'
        : 'text-slate-400 dark:text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#d4af37]',
      collapsed ? 'justify-center px-0' : ''
    ]"
    :title="collapsed ? label : ''"
  >
    <!-- Active indicator bar -->
    <div
      v-if="active"
      class="absolute right-0 top-1/4 bottom-1/4 w-[3px] bg-[#d4af37] rounded-l-full"
      style="box-shadow: 0 0 8px rgba(212,175,55,0.6)"
    ></div>

    <!-- Icon -->
    <i
      :class="[
        icon,
        'w-5 text-center text-base transition-all duration-200 flex-shrink-0',
        active ? 'text-[#d4af37] scale-110' : 'group-hover:text-[#d4af37] group-hover:scale-110',
        collapsed ? '' : 'ml-3'
      ]"
    ></i>

    <!-- Label (hidden when collapsed) -->
    <span
      v-if="!collapsed"
      class="text-sm font-bold tracking-wide truncate"
      :class="active ? 'text-[#d4af37]' : 'text-slate-500 dark:text-slate-400 group-hover:text-[#d4af37]'"
    >
      {{ label }}
    </span>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href:      String,
  icon:      String,
  label:     String,
  active:    Boolean,
  collapsed: {
    type:    Boolean,
    default: false,
  },
})
</script>
