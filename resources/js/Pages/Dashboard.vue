<template>
  <Head title="لوحة التحكم" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-0 py-6 space-y-6">

      <!-- ── Welcome ── -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
          <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">
            مرحباً، أستاذ {{ officeName }} 👋
          </h1>
          <p class="text-xs font-bold text-slate-400 mt-1">
            {{ today }} — لديك
            <span class="text-[#b8962d] font-black">{{ upcomingSessions.length }}</span>
            جلسة خلال الأيام القادمة
          </p>
        </div>
        <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 px-4 py-2 rounded-xl">
          <i class="fas fa-clock text-[#d4af37]"></i>
          {{ currentTime }}
        </div>
      </div>

      <!-- ── Stats Row ── -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <Link href="/sessions" class="group bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 shadow-sm hover:border-[#d4af37]/40 transition-all block">
          <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center">
              <i class="fas fa-gavel text-sm text-[#854F0B] dark:text-amber-400"></i>
            </div>
            <span class="text-[9px] font-black text-slate-300 dark:text-white/10 group-hover:text-[#d4af37]/30 transition-colors">
              <i class="fas fa-arrow-left"></i>
            </span>
          </div>
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">الجلسات الكلية</p>
          <p class="text-2xl font-black text-slate-900 dark:text-white">{{ stats.total_sessions }}</p>
        </Link>

        <Link href="/cases" class="group bg-[#EAF3DE] dark:bg-emerald-500/[0.06] border border-[#C0DD97] dark:border-emerald-500/20 rounded-xl p-4 shadow-sm hover:border-emerald-400/60 transition-all block">
          <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-white/60 dark:bg-white/5 flex items-center justify-center border border-[#C0DD97] dark:border-emerald-500/20">
              <i class="fas fa-folder-open text-sm text-[#3B6D11] dark:text-emerald-400"></i>
            </div>
            <span class="text-[9px] font-black text-[#3B6D11]/20 group-hover:text-[#3B6D11]/40 transition-colors">
              <i class="fas fa-arrow-left"></i>
            </span>
          </div>
          <p class="text-[10px] font-black text-[#3B6D11] dark:text-emerald-400 uppercase tracking-widest mb-1">قضايا نشطة</p>
          <p class="text-2xl font-black text-[#27500A] dark:text-emerald-300">{{ stats.open_cases }}</p>
        </Link>

        <Link href="/clients" class="group bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 shadow-sm hover:border-blue-300/50 transition-all block">
          <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-[#E6F1FB] dark:bg-blue-500/10 flex items-center justify-center">
              <i class="fas fa-user-tie text-sm text-[#185FA5] dark:text-blue-400"></i>
            </div>
            <span class="text-[9px] font-black text-slate-300 dark:text-white/10 group-hover:text-blue-300/50 transition-colors">
              <i class="fas fa-arrow-left"></i>
            </span>
          </div>
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">إجمالي الموكلين</p>
          <p class="text-2xl font-black text-slate-900 dark:text-white">{{ stats.total_clients }}</p>
        </Link>

        <div class="bg-[#FCEBEB] dark:bg-red-500/[0.06] border border-[#F7C1C1] dark:border-red-500/20 rounded-xl p-4 shadow-sm">
          <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-white/60 dark:bg-white/5 flex items-center justify-center border border-[#F7C1C1] dark:border-red-500/20">
              <i class="fas fa-exclamation-triangle text-sm text-[#A32D2D] dark:text-red-400"></i>
            </div>
          </div>
          <p class="text-[10px] font-black text-[#A32D2D] dark:text-red-400 uppercase tracking-widest mb-1">أتعاب معلقة</p>
          <p class="text-2xl font-black text-[#791F1F] dark:text-red-300">
            {{ formatNum(stats.pending_fees) }}
            <span class="text-xs font-bold text-[#E24B4A] dark:text-red-500">ج.م</span>
          </p>
        </div>

      </div>

      <!-- ── Row 2: جلسات + موكلين ── -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- جلسات الأيام القادمة -->
        <div class="lg:col-span-2 bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden shadow-sm">
          <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-lg bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-xs text-[#854F0B] dark:text-amber-400"></i>
              </div>
              <span class="text-sm font-black text-slate-800 dark:text-white">الجلسات القادمة</span>
            </div>
            <Link href="/sessions"
              class="text-[10px] font-black text-[#b8962d] border border-[#d4af37]/30 bg-[#FAEEDA] dark:bg-amber-500/10 dark:border-amber-500/20 px-3 py-1.5 rounded-lg hover:bg-[#FAC775]/40 transition-colors">
              عرض الكل <i class="fas fa-arrow-left mr-1 text-[9px]"></i>
            </Link>
          </div>
          <div class="divide-y divide-slate-50 dark:divide-white/5">
            <div v-for="(session, i) in upcomingSessions" :key="i"
              class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all">
              <!-- يوم / تاريخ -->
              <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex flex-col items-center justify-center flex-shrink-0">
                <span class="text-xs font-black text-[#b8962d]">{{ getDayNum(session.session_date) }}</span>
                <span class="text-[9px] font-black text-slate-400 uppercase">{{ getMonthName(session.session_date) }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-slate-700 dark:text-white truncate">
                  {{ session.case?.client?.name }}
                </p>
                <p class="text-[10px] font-bold text-slate-400 mt-0.5 truncate">
                  <i class="fas fa-gavel text-[9px] ml-1 text-[#d4af37]"></i>
                  {{ session.case?.case_number }} — {{ session.case?.court }}
                </p>
              </div>
              <div class="flex-shrink-0">
                <span :class="daysUntil(session.session_date) <= 2
                  ? 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20'
                  : 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-500/20'"
                  class="text-[10px] font-black px-2.5 py-1 rounded-lg border">
                  {{ daysUntil(session.session_date) === 0 ? 'اليوم' : 'بعد ' + daysUntil(session.session_date) + ' يوم' }}
                </span>
              </div>
            </div>
            <div v-if="!upcomingSessions.length" class="py-12 text-center text-sm font-bold text-slate-400">
              لا توجد جلسات قادمة
            </div>
          </div>
        </div>

        <!-- آخر الموكلين -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden shadow-sm">
          <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-lg bg-[#E6F1FB] dark:bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-user-tie text-xs text-[#185FA5] dark:text-blue-400"></i>
              </div>
              <span class="text-sm font-black text-slate-800 dark:text-white">آخر الموكلين</span>
            </div>
            <Link href="/clients"
              class="text-[10px] font-black text-[#185FA5] dark:text-blue-400 border border-blue-200 dark:border-blue-500/20 bg-[#E6F1FB] dark:bg-blue-500/10 px-3 py-1.5 rounded-lg hover:bg-[#B5D4F4]/30 transition-colors">
              عرض الكل <i class="fas fa-arrow-left mr-1 text-[9px]"></i>
            </Link>
          </div>
          <div class="divide-y divide-slate-50 dark:divide-white/5">
            <Link v-for="client in recentClients" :key="client.id" :href="`/clients/${client.id}`"
              class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all block">
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0"
                :class="client.type === 'company'
                  ? 'bg-[#FAEEDA] dark:bg-amber-500/10 text-[#854F0B] dark:text-amber-400'
                  : 'bg-[#EAF3DE] dark:bg-emerald-500/10 text-[#3B6D11] dark:text-emerald-400'">
                {{ client.name?.charAt(0) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-slate-700 dark:text-white truncate">{{ client.name }}</p>
                <p class="text-[10px] font-bold text-slate-400">
                  <i class="fas fa-phone text-[9px] ml-1"></i>{{ client.phone }}
                </p>
              </div>
              <i class="fas fa-chevron-left text-[10px] text-slate-300 dark:text-white/20 flex-shrink-0"></i>
            </Link>
            <div v-if="!recentClients.length" class="py-10 text-center text-sm font-bold text-slate-400">
              لا يوجد موكلين
            </div>
          </div>
        </div>

      </div>

      <!-- ── Row 3: قضايا + مدفوعات ── -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- آخر القضايا -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden shadow-sm">
          <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-lg bg-[#EAF3DE] dark:bg-emerald-500/10 flex items-center justify-center">
                <i class="fas fa-briefcase text-xs text-[#3B6D11] dark:text-emerald-400"></i>
              </div>
              <span class="text-sm font-black text-slate-800 dark:text-white">آخر القضايا</span>
            </div>
            <Link href="/cases"
              class="text-[10px] font-black text-[#3B6D11] dark:text-emerald-400 border border-[#C0DD97] dark:border-emerald-500/20 bg-[#EAF3DE] dark:bg-emerald-500/10 px-3 py-1.5 rounded-lg hover:bg-[#C0DD97]/40 transition-colors">
              عرض الكل <i class="fas fa-arrow-left mr-1 text-[9px]"></i>
            </Link>
          </div>
          <div class="divide-y divide-slate-50 dark:divide-white/5">
            <Link v-for="c in recentCases" :key="c.id" :href="`/cases/${c.id}`"
              class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all block">
              <div class="w-9 h-9 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-gavel text-xs text-[#854F0B] dark:text-amber-400"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-slate-700 dark:text-white">{{ c.case_number }}</p>
                <p class="text-[10px] font-bold text-slate-400 truncate">
                  {{ c.client?.name }} — {{ c.court }}
                </p>
              </div>
              <span :class="statusPill(c.status)"
                class="text-[9px] font-black px-2.5 py-1 rounded-full border flex-shrink-0">
                {{ translateStatus(c.status) }}
              </span>
            </Link>
            <div v-if="!recentCases.length" class="py-10 text-center text-sm font-bold text-slate-400">
              لا توجد قضايا
            </div>
          </div>
        </div>

        <!-- آخر المدفوعات -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden shadow-sm">
          <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-lg bg-[#EAF3DE] dark:bg-emerald-500/10 flex items-center justify-center">
                <i class="fas fa-coins text-xs text-[#3B6D11] dark:text-emerald-400"></i>
              </div>
              <span class="text-sm font-black text-slate-800 dark:text-white">آخر المدفوعات</span>
            </div>
          </div>
          <div class="divide-y divide-slate-50 dark:divide-white/5">
            <div v-for="payment in recentPayments" :key="payment.id"
              class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all">
              <div class="w-9 h-9 rounded-lg bg-[#EAF3DE] dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-money-bill-wave text-xs text-[#3B6D11] dark:text-emerald-400"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-slate-700 dark:text-white truncate">
                  {{ payment.case?.client?.name }}
                </p>
                <p class="text-[10px] font-bold text-slate-400">
                  {{ payment.case?.case_number }} — {{ formatDate(payment.payment_date) }}
                </p>
              </div>
              <div class="text-left flex-shrink-0">
                <p class="text-sm font-black text-[#27500A] dark:text-emerald-400">{{ formatNum(payment.amount) }}</p>
                <p class="text-[9px] font-bold text-[#639922] dark:text-emerald-500">ج.م</p>
              </div>
            </div>
            <div v-if="!recentPayments.length" class="py-10 text-center text-sm font-bold text-slate-400">
              لا توجد مدفوعات
            </div>
          </div>
        </div>

      </div>

    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      total_sessions: 0,
      open_cases:     0,
      total_clients:  0,
      pending_fees:   0,
    }),
  },
  upcomingSessions: { type: Array, default: () => [] }, // 7 جلسات قادمة
  recentClients:    { type: Array, default: () => [] }, // آخر 5 موكلين
  recentCases:      { type: Array, default: () => [] }, // آخر 5 قضايا
  recentPayments:   { type: Array, default: () => [] }, // آخر 5 مدفوعات
  officeName:       { type: String, default: 'محمود' },
})


const currentTime = ref('')
let clockInterval = null

const updateClock = () => {
  currentTime.value = new Date().toLocaleTimeString('ar-EG', {
    hour: '2-digit', minute: '2-digit',
  })
}

onMounted(() => { updateClock(); clockInterval = setInterval(updateClock, 60000) })
onBeforeUnmount(() => clearInterval(clockInterval))

// ── Today ─────────────────────────────────────────
const today = new Date().toLocaleDateString('ar-EG', {
  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
})

// ── Helpers ───────────────────────────────────────
const formatDate = (date) => {
  if (!date) return '---'
  return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' })
}

const formatNum = (val) => Number(val || 0).toLocaleString('ar-EG')

const getDayNum = (date) => {
  if (!date) return '--'
  return new Date(date).getDate()
}

const getMonthName = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('ar-EG', { month: 'short' })
}

const daysUntil = (date) => {
  if (!date) return 0
  return Math.max(0, Math.ceil((new Date(date) - new Date()) / (1000 * 60 * 60 * 24)))
}

const translateStatus = (s) => ({ pending: 'قيد الإعداد', open: 'نشطة', closed: 'مغلقة' }[s] || s)

const statusPill = (s) => {
  if (s === 'open')   return 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20'
  if (s === 'closed') return 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/10'
  return 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'
}
</script>
