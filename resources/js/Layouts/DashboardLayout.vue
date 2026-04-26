<template>
  <div :class="{ 'dark': isDark }">
    <div class="min-h-screen bg-[#f8fafc] dark:bg-[#0f1115] transition-colors duration-300 flex">

      <transition name="fade">
        <div
          v-if="isSidebarOpen"
          @click="isSidebarOpen = false"
          class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
        ></div>
      </transition>


      <aside
        class="fixed right-0 z-50 flex flex-col bg-white dark:bg-[#161d27] border-l border-slate-200 dark:border-white/5 shadow-2xl transition-all duration-300 ease-in-out lg:static lg:translate-x-0 pt-6"
        :class="[
          isSidebarOpen ? 'translate-x-0' : 'translate-x-full',
          isSidebarCollapsed ? 'w-[68px]' : 'w-64'
        ]"
      >

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 h-20 border-b border-slate-100 dark:border-white/5 flex-shrink-0 pb-5">
          <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37] to-[#b8962d] rounded-xl flex items-center justify-center shadow-lg shadow-[#d4af37]/20 flex-shrink-0">
            <i class="fas fa-balance-scale text-base text-[#1e293b]"></i>
          </div>
          <transition name="fade-fast">
            <div v-if="!isSidebarCollapsed" class="overflow-hidden">
              <h1 class="text-slate-800 dark:text-white font-black text-base tracking-tight leading-none whitespace-nowrap">مكتب العدالة</h1>
              <span class="text-[#d4af37] text-[9px] font-bold uppercase tracking-[0.2em]">Law Firm Pro</span>
            </div>
          </transition>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar">

          <NavLink href="/"
            icon="fas fa-chart-pie"
            label="لوحة التحكم"
            :active="$page.url === '/'"
            :collapsed="isSidebarCollapsed"
          />

          <div v-if="!isSidebarCollapsed" class="pt-4 pb-1.5 px-3">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">القانونية</span>
          </div>
          <div v-else class="pt-3 pb-1 flex justify-center">
            <div class="w-5 h-px bg-slate-200 dark:bg-white/10"></div>
          </div>

          <NavLink href="/cases"
            icon="fas fa-folder-open"
            label="إدارة القضايا"
            :active="$page.url.startsWith('/cases')"
            :collapsed="isSidebarCollapsed"
          />
          <NavLink href="/clients"
            icon="fas fa-user-tie"
            label="الموكلين"
            :active="$page.url.startsWith('/clients')"
            :collapsed="isSidebarCollapsed"
          />
          <NavLink href="/sessions"
            icon="fas fa-gavel"
            label="الجلسات"
            :active="$page.url.startsWith('/sessions')"
            :collapsed="isSidebarCollapsed"
          />
          <NavLink href="/finances"
            icon="fas fa-hand-holding-usd"
            label="الماليات"
            :active="$page.url.startsWith('/finances')"
            :collapsed="isSidebarCollapsed"
          />
          <!-- <NavLink href="/tasks"
            icon="fas fa-calendar-check"
            label="جدول المهام"
            :active="$page.url.startsWith('/tasks')"
            :collapsed="isSidebarCollapsed"
          /> -->

          <div v-if="!isSidebarCollapsed" class="pt-4 pb-1.5 px-3">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">الإعدادات</span>
          </div>
          <div v-else class="pt-3 pb-1 flex justify-center">
            <div class="w-5 h-px bg-slate-200 dark:bg-white/10"></div>
          </div>

          <NavLink href="/profile"
            icon="fas fa-building"
            label="بيانات المكتب"
            :active="$page.url.startsWith('/profile')"
            :collapsed="isSidebarCollapsed"
          />

        </nav>

        <!-- User + Dark Toggle -->
        <div class="flex-shrink-0 p-3 border-t border-slate-100 dark:border-white/5">

          <!-- Expanded -->
          <template v-if="!isSidebarCollapsed">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5">
              <div class="w-9 h-9 rounded-xl bg-[#d4af37]/15 border border-[#d4af37]/20 flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-black text-[#d4af37]">م</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-slate-700 dark:text-white truncate">مكتب العدالة</p>
                <p class="text-[10px] text-slate-400 truncate">مدير النظام</p>
              </div>
              <button
                @click="toggleDark"
                class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-[#d4af37] hover:bg-[#d4af37]/10 transition-all flex-shrink-0"
                :title="isDark ? 'الوضع النهاري' : 'الوضع الليلي'"
              >
                <i :class="isDark ? 'fas fa-sun text-xs' : 'fas fa-moon text-xs'"></i>
              </button>
            </div>
          </template>

          <!-- Collapsed -->
          <template v-else>
            <div class="flex flex-col items-center gap-2">
              <div class="w-9 h-9 rounded-xl bg-[#d4af37]/15 border border-[#d4af37]/20 flex items-center justify-center">
                <span class="text-sm font-black text-[#d4af37]">م</span>
              </div>
              <button
                @click="toggleDark"
                class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-[#d4af37] hover:bg-[#d4af37]/10 transition-all"
              >
                <i :class="isDark ? 'fas fa-sun text-xs' : 'fas fa-moon text-xs'"></i>
              </button>
            </div>
          </template>

          <!-- Collapse toggle (desktop only) -->
          <button
            @click="isSidebarCollapsed = !isSidebarCollapsed"
            class="hidden lg:flex w-full mt-2 items-center justify-center h-7 rounded-xl text-slate-300 dark:text-slate-600 hover:text-[#d4af37] hover:bg-[#d4af37]/5 transition-all text-xs"
          >
            <i :class="isSidebarCollapsed ? 'fas fa-chevron-left' : 'fas fa-chevron-right'"></i>
          </button>
        </div>
      </aside>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Main -->
      <!-- ══════════════════════════════════════════════ -->
      <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Header -->
        <header class="h-16 bg-white/80 dark:bg-[#0f1115]/80 backdrop-blur-md border-b border-slate-200 dark:border-white/5 flex items-center justify-between px-5 lg:px-7 sticky top-0 z-30 flex-shrink-0">

          <!-- Mobile hamburger -->
          <button
            @click="isSidebarOpen = true"
            class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:bg-[#d4af37]/5 transition-all"
          >
            <i class="fas fa-bars text-sm"></i>
          </button>

          <!-- Date (desktop) -->
          <div class="hidden lg:flex flex-col">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">تاريخ اليوم</span>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ todayFormatted }}</span>
          </div>

          <!-- Mobile: page title placeholder -->
          <div class="lg:hidden flex-1"></div>

          <!-- Actions -->
          <div class="flex items-center gap-2">

            <!-- Notification Bell -->
            <div class="relative" ref="notifRef">
              <button
                @click="notifOpen = !notifOpen"
                class="relative w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-[#d4af37] hover:bg-[#d4af37]/5 transition-all border border-transparent hover:border-[#d4af37]/20"
                :class="{ 'text-[#d4af37] bg-[#d4af37]/5 border-[#d4af37]/20': notifOpen }"
              >
                <i class="fas fa-bell text-sm"></i>
                <span v-if="unreadCount > 0"
                  class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 rounded-full border-2 border-white dark:border-[#0f1115] flex items-center justify-center">
                  <span class="text-[8px] font-black text-white">{{ unreadCount }}</span>
                </span>
              </button>

              <!-- Notification Dropdown -->
              <transition name="dropdown">
                <div
                  v-if="notifOpen"
                  class="absolute left-0 top-12 w-80 bg-white dark:bg-[#161d27] rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl shadow-slate-200/50 dark:shadow-black/40 overflow-hidden z-50"
                >
                  <!-- Header -->
                  <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-white/5">
                    <p class="text-xs font-black text-slate-700 dark:text-white">الإشعارات</p>
                    <button
                      @click="markAllRead"
                      class="text-[10px] font-bold text-[#d4af37] hover:text-[#b8962d] transition-colors"
                    >
                      تعيين الكل كمقروء
                    </button>
                  </div>

                  <!-- List -->
                  <div class="max-h-72 overflow-y-auto custom-scrollbar">
                    <div
                      v-for="n in notifications"
                      :key="n.id"
                      @click="readNotif(n)"
                      class="flex items-start gap-3 px-4 py-3 cursor-pointer transition-colors hover:bg-slate-50 dark:hover:bg-white/[0.02] border-b border-slate-50 dark:border-white/[0.03] last:border-0"
                      :class="{ 'bg-[#d4af37]/[0.04]': !n.read }"
                    >
                      <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                        :class="notifIconBg(n.type)">
                        <i :class="[notifIcon(n.type), 'text-xs', notifIconColor(n.type)]"></i>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200 leading-snug">{{ n.title }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ n.body }}</p>
                        <p class="text-[9px] text-slate-300 dark:text-slate-600 mt-1">{{ n.time }}</p>
                      </div>
                      <div v-if="!n.read" class="w-1.5 h-1.5 rounded-full bg-[#d4af37] mt-2 flex-shrink-0"></div>
                    </div>

                    <div v-if="!notifications.length" class="px-4 py-10 text-center">
                      <i class="fas fa-bell-slash text-2xl text-slate-200 dark:text-slate-700 block mb-2"></i>
                      <p class="text-xs text-slate-400">لا توجد إشعارات</p>
                    </div>
                  </div>

                  <!-- Footer -->
                  <div class="px-4 py-2.5 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/10">
                    <button class="w-full text-[10px] font-bold text-slate-400 hover:text-[#d4af37] transition-colors text-center">
                      عرض كل الإشعارات
                    </button>
                  </div>
                </div>
              </transition>
            </div>

          </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto custom-scrollbar px-5">
          <slot />
        </main>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue'
import { Link } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'

// ── Sidebar ───────────────────────────────────────────────────────────────────
const isSidebarOpen      = ref(false)
const isSidebarCollapsed = ref(false)

// ── Dark Mode ─────────────────────────────────────────────────────────────────
const isDark = ref(false)

const toggleDark = () => {
  isDark.value = !isDark.value
  document.documentElement.classList.toggle('dark', isDark.value)
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}

onMounted(() => {
  const saved = localStorage.getItem('theme')
  if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDark.value = true
    document.documentElement.classList.add('dark')
  }

  // Close notif on outside click
  document.addEventListener('click', handleOutsideClick)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleOutsideClick)
})

// ── Today Date ────────────────────────────────────────────────────────────────
const todayFormatted = computed(() =>
  new Date().toLocaleDateString('ar-EG', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
)

// ── Notifications ─────────────────────────────────────────────────────────────
const notifOpen = ref(false)
const notifRef  = ref(null)

const notifications = reactive([
  { id: 1, type: 'session', title: 'جلسة غداً', body: 'جلسة قضية 2024/1532 في محكمة الجيزة الساعة 10 صباحاً', time: 'منذ 5 دقائق', read: false },
  { id: 2, type: 'payment', title: 'دفعة جديدة', body: 'تم استلام دفعة 5,000 جنيه من الموكل أحمد محمود', time: 'منذ ساعة', read: false },
  { id: 3, type: 'task',    title: 'مهمة متأخرة', body: 'مراجعة عقد الإيجار للموكل فاطمة السيد متأخرة 2 أيام', time: 'أمس', read: false },
  { id: 4, type: 'case',   title: 'قضية جديدة', body: 'تم إضافة قضية جديدة برقم 2024/1877', time: 'أمس', read: true },
])

const unreadCount = computed(() => notifications.filter(n => !n.read).length)

const readNotif = (n) => { n.read = true }
const markAllRead = () => notifications.forEach(n => n.read = true)

const notifIconBg = (type) => ({
  session: 'bg-[#FAEEDA] dark:bg-amber-500/10',
  payment: 'bg-[#EAF3DE] dark:bg-emerald-500/10',
  task:    'bg-red-50 dark:bg-red-500/10',
  case:    'bg-blue-50 dark:bg-blue-500/10',
}[type] || 'bg-slate-100 dark:bg-white/5')

const notifIcon = (type) => ({
  session: 'fas fa-gavel',
  payment: 'fas fa-coins',
  task:    'fas fa-clock',
  case:    'fas fa-folder-open',
}[type] || 'fas fa-bell')

const notifIconColor = (type) => ({
  session: 'text-[#854F0B] dark:text-amber-400',
  payment: 'text-[#3B6D11] dark:text-emerald-400',
  task:    'text-red-500',
  case:    'text-blue-500',
}[type] || 'text-slate-400')

const handleOutsideClick = (e) => {
  if (notifRef.value && !notifRef.value.contains(e.target)) {
    notifOpen.value = false
  }
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(212,175,55,0.1); border-radius: 10px; }
.custom-scrollbar:hover::-webkit-scrollbar-thumb { background: rgba(212,175,55,0.25); }

.fade-enter-active, .fade-leave-active { transition: opacity 0.25s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.fade-fast-enter-active, .fade-fast-leave-active { transition: opacity 0.15s; }
.fade-fast-enter-from, .fade-fast-leave-to { opacity: 0; }

.dropdown-enter-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.dropdown-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px) scale(0.98); }
</style>
