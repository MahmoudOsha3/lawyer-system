<template>
  <Head title="الماليات" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-0 py-6 space-y-5">

      <!-- ── Header ── -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">السجل المالي</h1>
          <p class="text-xs font-bold text-slate-400 mt-1">متابعة الأتعاب والمصاريف الخاصة بالقضايا</p>
        </div>
      </div>

      <!-- ── Stats Row ── -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-[#EAF3DE] dark:bg-emerald-500/[0.06] border border-[#C0DD97] dark:border-emerald-500/20 rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-white/60 dark:bg-white/5 border border-[#C0DD97] dark:border-emerald-500/20 flex items-center justify-center mb-3">
            <i class="fas fa-hand-holding-usd text-sm text-[#3B6D11] dark:text-emerald-400"></i>
          </div>
          <p class="text-[10px] font-black text-[#3B6D11] dark:text-emerald-400 uppercase tracking-widest mb-1">أتعاب مدفوعة</p>
          <p class="text-xl font-black text-[#27500A] dark:text-emerald-300">
            {{ formatNum(stats.total_payments) }}
            <span class="text-xs font-bold text-[#639922] dark:text-emerald-500">ج.م</span>
          </p>
        </div>
        <div class="bg-[#FCEBEB] dark:bg-red-500/[0.06] border border-[#F7C1C1] dark:border-red-500/20 rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-white/60 dark:bg-white/5 border border-[#F7C1C1] dark:border-red-500/20 flex items-center justify-center mb-3">
            <i class="fas fa-clock text-sm text-[#A32D2D] dark:text-red-400"></i>
          </div>
          <p class="text-[10px] font-black text-[#A32D2D] dark:text-red-400 uppercase tracking-widest mb-1">أتعاب معلقة</p>
          <p class="text-xl font-black text-[#791F1F] dark:text-red-300">
            {{ formatNum(stats.pending_fees) }}
            <span class="text-xs font-bold text-[#E24B4A] dark:text-red-500">ج.م</span>
          </p>
        </div>
        <div class="bg-[#EAF3DE] dark:bg-emerald-500/[0.06] border border-[#C0DD97] dark:border-emerald-500/20 rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-white/60 dark:bg-white/5 border border-[#C0DD97] dark:border-emerald-500/20 flex items-center justify-center mb-3">
            <i class="fas fa-check-circle text-sm text-[#3B6D11] dark:text-emerald-400"></i>
          </div>
          <p class="text-[10px] font-black text-[#3B6D11] dark:text-emerald-400 uppercase tracking-widest mb-1">مصاريف مدفوعة</p>
          <p class="text-xl font-black text-[#27500A] dark:text-emerald-300">
            {{ formatNum(stats.expenses_paid) }}
            <span class="text-xs font-bold text-[#639922] dark:text-emerald-500">ج.م</span>
          </p>
        </div>
        <div class="bg-[#FAEEDA] dark:bg-amber-500/[0.06] border border-[#FAC775] dark:border-amber-500/20 rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-white/60 dark:bg-white/5 border border-[#FAC775] dark:border-amber-500/20 flex items-center justify-center mb-3">
            <i class="fas fa-exclamation-circle text-sm text-[#854F0B] dark:text-amber-400"></i>
          </div>
          <p class="text-[10px] font-black text-[#854F0B] dark:text-amber-400 uppercase tracking-widest mb-1">مصاريف معلقة</p>
          <p class="text-xl font-black text-[#633806] dark:text-amber-300">
            {{ formatNum(stats.expenses_pending) }}
            <span class="text-xs font-bold text-[#BA7517] dark:text-amber-500">ج.م</span>
          </p>
        </div>
      </div>

      <!-- ── Tabs + Filters ── -->
      <div class="bg-white dark:bg-[#111827] border border-slate-100 dark:border-white/5 rounded-2xl overflow-hidden shadow-sm">

        <!-- Tabs -->
        <div class="flex border-b border-slate-100 dark:border-white/5">
          <button v-for="tab in tabs" :key="tab.key" @click="switchTab(tab.key)"
            :class="activeTab === tab.key
              ? 'border-b-2 border-[#d4af37] text-[#b8962d] dark:text-[#d4af37] bg-[#FAEEDA]/30 dark:bg-amber-500/5'
              : 'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 border-b-2 border-transparent'"
            class="px-6 py-3.5 text-xs font-black transition-all -mb-px flex items-center gap-2">
            <i :class="tab.icon" class="text-[10px]"></i>
            {{ tab.label }}
          </button>
        </div>

        <!-- Date Filters -->
        <div class="px-5 py-3.5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/10">
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">
              <i class="fas fa-filter ml-1"></i> الفترة الزمنية:
            </span>
            <div class="flex items-center gap-2 flex-wrap">
              <div class="relative">
                <i class="fas fa-calendar-alt absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                <input v-model="filters.from" type="date" class="filter-input pr-8 w-36" />
              </div>
              <span class="text-xs font-black text-slate-400">إلى</span>
              <div class="relative">
                <i class="fas fa-calendar-alt absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                <input v-model="filters.to" type="date" class="filter-input pr-8 w-36" />
              </div>
              <button @click="applyFilters"
                class="px-4 py-2 rounded-lg text-xs font-black bg-[#d4af37] text-[#1e272e] hover:bg-[#b8962d] transition-all">
                <i class="fas fa-search ml-1 text-[10px]"></i> تطبيق
              </button>
              <button @click="resetFilters"
                class="px-3 py-2 rounded-lg text-xs font-black border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                <i class="fas fa-times text-[10px]"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- ════════════════════════════════════
             TAB: الأتعاب
        ════════════════════════════════════ -->
        <div v-if="activeTab === 'payments'">

          <!-- MOBILE: كروت -->
          <div class="md:hidden p-4 space-y-3">
            <div v-if="!payments.data.length" class="py-14 text-center">
              <i class="fas fa-money-bill-wave text-2xl text-slate-200 dark:text-slate-700 mb-2 block"></i>
              <p class="text-sm font-bold text-slate-400">لا توجد مدفوعات في هذه الفترة</p>
            </div>
            <div v-for="p in payments.data" :key="p.id"
              class="bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 rounded-xl p-4">
              <!-- Header -->
              <div class="flex items-center justify-between mb-3">
                <div>
                  <p class="text-sm font-black text-slate-800 dark:text-white">{{ p.case?.client?.name }}</p>
                  <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ p.case?.case_number }}</p>
                </div>
                <!-- المبلغ -->
                <div class="text-left">
                  <p class="text-lg font-black text-[#27500A] dark:text-emerald-400 leading-tight">{{ formatNum(p.amount) }}</p>
                  <p class="text-[10px] font-bold text-[#639922] dark:text-emerald-500 text-left">ج.م</p>
                </div>
              </div>
              <!-- Footer -->
              <div class="flex items-center justify-between pt-2.5 border-t border-slate-200 dark:border-white/5">
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                  <i class="far fa-calendar-alt text-[#d4af37] text-[10px]"></i>
                  {{ formatDate(p.payment_date) }}
                </div>
                <p v-if="p.notes" class="text-[10px] font-bold text-slate-400 max-w-[140px] truncate">{{ p.notes }}</p>
              </div>
            </div>
          </div>

          <!-- DESKTOP: جدول -->
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-right">
              <thead class="bg-slate-50 dark:bg-black/20 border-b border-slate-100 dark:border-white/5">
                <tr>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">الموكل / القضية</th>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">تاريخ الدفع</th>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">المبلغ</th>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">ملاحظات</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                <tr v-for="p in payments.data" :key="p.id"
                  class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all">
                  <td class="px-5 py-4">
                    <p class="text-sm font-black text-slate-700 dark:text-white">{{ p.case?.client?.name }}</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ p.case?.case_number }}</p>
                  </td>
                  <td class="px-5 py-4">
                    <span class="text-sm font-bold text-slate-600 dark:text-slate-300 font-mono">{{ formatDate(p.payment_date) }}</span>
                  </td>
                  <td class="px-5 py-4">
                    <span class="text-sm font-black text-[#27500A] dark:text-emerald-400">{{ formatNum(p.amount) }}</span>
                    <span class="text-[10px] font-bold text-[#639922] dark:text-emerald-500 mr-0.5">ج.م</span>
                  </td>
                  <td class="px-5 py-4 text-xs font-bold text-slate-400">{{ p.notes || '—' }}</td>
                </tr>
                <tr v-if="!payments.data.length">
                  <td colspan="4" class="py-14 text-center text-sm font-bold text-slate-400">
                    <i class="fas fa-money-bill-wave text-2xl text-slate-200 dark:text-slate-700 mb-2 block"></i>
                    لا توجد مدفوعات في هذه الفترة
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination: payments -->
          <div v-if="payments.last_page > 1"
            class="px-5 py-3.5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-black/10">
            <p class="text-xs font-bold text-slate-400">عرض {{ payments.from }}–{{ payments.to }} من {{ payments.total }}</p>
            <div class="flex items-center gap-1.5">
              <a v-if="payments.prev_page_url" :href="payments.prev_page_url"
                class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
                <i class="fas fa-chevron-right"></i>
              </a>
              <span class="px-3 text-xs font-black text-slate-600 dark:text-white">
                {{ payments.current_page }} / {{ payments.last_page }}
              </span>
              <a v-if="payments.next_page_url" :href="payments.next_page_url"
                class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
                <i class="fas fa-chevron-left"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- ════════════════════════════════════
             TAB: مصاريف الموكلين
        ════════════════════════════════════ -->
        <div v-if="activeTab === 'expenses'">

          <!-- MOBILE: كروت -->
          <div class="md:hidden p-4 space-y-3">
            <div v-if="!caseExpenses.data.length" class="py-14 text-center">
              <i class="fas fa-receipt text-2xl text-slate-200 dark:text-slate-700 mb-2 block"></i>
              <p class="text-sm font-bold text-slate-400">لا توجد مصاريف في هذه الفترة</p>
            </div>
            <div v-for="e in caseExpenses.data" :key="e.id"
              class="bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 rounded-xl p-4">
              <!-- Header -->
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-black text-slate-800 dark:text-white">{{ e.case?.client?.name }}</p>
                  <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ e.case?.case_number }}</p>
                  <p class="text-xs font-bold text-slate-600 dark:text-slate-300 mt-1.5">{{ e.title }}</p>
                </div>
                <!-- المبلغ + الحالة -->
                <div class="text-left mr-3 flex-shrink-0">
                  <p class="text-lg font-black text-[#791F1F] dark:text-red-400 leading-tight">{{ formatNum(e.amount) }}</p>
                  <p class="text-[10px] font-bold text-[#E24B4A] dark:text-red-500 text-left mb-1.5">ج.م</p>
                  <span v-if="e.status === 'paid'"
                    class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-0.5 rounded-full bg-[#EAF3DE] dark:bg-emerald-500/10 text-[#3B6D11] dark:text-emerald-400 border border-[#C0DD97] dark:border-emerald-500/20">
                    <i class="fas fa-check text-[8px]"></i> مدفوع
                  </span>
                  <span v-else
                    class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-0.5 rounded-full bg-[#FAEEDA] dark:bg-amber-500/10 text-[#854F0B] dark:text-amber-400 border border-[#FAC775] dark:border-amber-500/20">
                    <i class="fas fa-clock text-[8px]"></i> معلق
                  </span>
                </div>
              </div>
              <!-- Footer: التاريخ -->
              <div class="flex items-center gap-1.5 pt-2.5 border-t border-slate-200 dark:border-white/5">
                <i class="far fa-calendar-alt text-[#d4af37] text-[10px]"></i>
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ formatDate(e.expense_date) }}</span>
              </div>
            </div>
          </div>

          <!-- DESKTOP: جدول -->
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-right">
              <thead class="bg-slate-50 dark:bg-black/20 border-b border-slate-100 dark:border-white/5">
                <tr>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">الموكل / القضية</th>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">البيان</th>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">تاريخ المصروف</th>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">المبلغ</th>
                  <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">الحالة</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                <tr v-for="e in caseExpenses.data" :key="e.id"
                  class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all">
                  <td class="px-5 py-4">
                    <p class="text-sm font-black text-slate-700 dark:text-white">{{ e.case?.client?.name }}</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ e.case?.case_number }}</p>
                  </td>
                  <td class="px-5 py-4">
                    <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ e.title }}</span>
                  </td>
                  <td class="px-5 py-4">
                    <span class="text-sm font-bold text-slate-600 dark:text-slate-300 font-mono">{{ formatDate(e.expense_date) }}</span>
                  </td>
                  <td class="px-5 py-4">
                    <span class="text-sm font-black text-[#791F1F] dark:text-red-400">{{ formatNum(e.amount) }}</span>
                    <span class="text-[10px] font-bold text-[#E24B4A] dark:text-red-500 mr-0.5">ج.م</span>
                  </td>
                  <td class="px-5 py-4">
                    <span v-if="e.status === 'paid'"
                      class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-full bg-[#EAF3DE] dark:bg-emerald-500/10 text-[#3B6D11] dark:text-emerald-400 border border-[#C0DD97] dark:border-emerald-500/20">
                      <i class="fas fa-check text-[9px]"></i> مدفوع
                    </span>
                    <span v-else
                      class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-full bg-[#FAEEDA] dark:bg-amber-500/10 text-[#854F0B] dark:text-amber-400 border border-[#FAC775] dark:border-amber-500/20">
                      <i class="fas fa-clock text-[9px]"></i> معلق
                    </span>
                  </td>
                </tr>
                <tr v-if="!caseExpenses.data.length">
                  <td colspan="5" class="py-14 text-center text-sm font-bold text-slate-400">
                    <i class="fas fa-receipt text-2xl text-slate-200 dark:text-slate-700 mb-2 block"></i>
                    لا توجد مصاريف في هذه الفترة
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination: expenses -->
          <div v-if="caseExpenses.last_page > 1"
            class="px-5 py-3.5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-black/10">
            <p class="text-xs font-bold text-slate-400">عرض {{ caseExpenses.from }}–{{ caseExpenses.to }} من {{ caseExpenses.total }}</p>
            <div class="flex items-center gap-1.5">
              <a v-if="caseExpenses.prev_page_url" :href="caseExpenses.prev_page_url"
                class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
                <i class="fas fa-chevron-right"></i>
              </a>
              <span class="px-3 text-xs font-black text-slate-600 dark:text-white">
                {{ caseExpenses.current_page }} / {{ caseExpenses.last_page }}
              </span>
              <a v-if="caseExpenses.next_page_url" :href="caseExpenses.next_page_url"
                class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
                <i class="fas fa-chevron-left"></i>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
  stats:        { type: Object, default: () => ({}) },
  payments:     { type: Object, default: () => ({ data: [], total: 0, last_page: 1 }) },
  caseExpenses: { type: Object, default: () => ({ data: [], total: 0, last_page: 1 }) },
  filters:      { type: Object, default: () => ({}) },
})

const tabs = [
  { key: 'payments', label: 'الأتعاب المدفوعة', icon: 'fas fa-hand-holding-usd' },
  { key: 'expenses', label: 'مصاريف الموكلين',  icon: 'fas fa-receipt'           },
]

const activeTab = ref(props.filters?.tab || 'payments')

const switchTab = (key) => {
  activeTab.value = key
  router.get('/finances',
    { tab: key, from: filters.from || undefined, to: filters.to || undefined },
    { preserveState: true, replace: true }
  )
}

const filters = reactive({
  from: props.filters?.from || '',
  to:   props.filters?.to   || '',
})

const applyFilters = () => {
  router.get('/finances', {
    tab:  activeTab.value,
    from: filters.from || undefined,
    to:   filters.to   || undefined,
  }, { preserveState: true, replace: true })
}

const resetFilters = () => {
  filters.from = ''
  filters.to   = ''
  router.get('/finances', { tab: activeTab.value }, { preserveState: false, replace: true })
}

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' })
}

const formatNum = (val) => Number(val || 0).toLocaleString('ar-EG')
</script>

<style scoped>
.filter-input {
  background-color: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 0.625rem;
  padding: 0.5rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 700;
  outline: none;
  transition: all 0.2s;
  color: #1e293b;
}
.filter-input:focus {
  border-color: #d4af37;
  background-color: #fff;
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}
:where(.dark) .filter-input {
  background-color: rgba(0, 0, 0, 0.2);
  border-color: rgba(255, 255, 255, 0.08);
  color: white;
}
:where(.dark) .filter-input:focus { border-color: #d4af37; }
</style>
