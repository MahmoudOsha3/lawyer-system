<template>
    <Head title="إدارة الجلسات" />

    <DashboardLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 pt-6">

            <!-- ── Header ── -->
            <div class="mb-6">
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">إدارة الجلسات</h1>
                <p class="text-xs font-bold text-slate-400 mt-1">تابع مواعيد ونتائج جلسات القضايا الخاصة بك</p>
            </div>

            <!-- ── Stats ── -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">

                <!-- إجمالي -->
                <div class="stat-card stat-total relative overflow-hidden rounded-2xl p-4 border">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="stat-label text-[10px] font-black uppercase tracking-widest mb-2">إجمالي الجلسات</p>
                            <p class="text-3xl font-black stat-total-num leading-none">{{ sessionsData.length }}</p>
                        </div>
                        <div class="stat-icon-wrap stat-total-icon w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                        </div>
                    </div>

                    <svg class="sparkline absolute bottom-0 left-0 right-0 h-7 w-full opacity-10" viewBox="0 0 100 28" preserveAspectRatio="none">
                        <polyline fill="none" stroke="#7F77DD" stroke-width="2.5" points="0,20 15,15 30,18 45,10 60,12 75,6 90,8 100,4"/>
                    </svg>
                </div>

                <!-- تمت -->
                <div class="stat-card stat-completed relative overflow-hidden rounded-2xl p-4 border">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="stat-label text-[10px] font-black uppercase tracking-widest mb-2 stat-completed-label">جلسات تمت</p>
                            <p class="text-3xl font-black stat-completed-num leading-none">{{ completedSessions }}</p>
                        </div>
                        <div class="stat-icon-wrap stat-completed-icon w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/><polyline points="9 12 11 14 15 10"/>
                            </svg>
                        </div>
                    </div>

                    <svg class="sparkline absolute bottom-0 left-0 right-0 h-7 w-full opacity-10" viewBox="0 0 100 28" preserveAspectRatio="none">
                        <polyline fill="none" stroke="#639922" stroke-width="2.5" points="0,22 20,18 40,14 60,10 80,6 100,3"/>
                    </svg>
                </div>

                <!-- متبقية -->
                <div class="stat-card stat-pending relative overflow-hidden rounded-2xl p-4 border">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="stat-label text-[10px] font-black uppercase tracking-widest mb-2 stat-pending-label">جلسات متبقية</p>
                            <p class="text-3xl font-black stat-pending-num leading-none">{{ pendingSessions }}</p>
                        </div>
                        <div class="stat-icon-wrap stat-pending-icon w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/>
                            </svg>
                        </div>
                    </div>

                    <svg class="sparkline absolute bottom-0 left-0 right-0 h-7 w-full opacity-10" viewBox="0 0 100 28" preserveAspectRatio="none">
                        <polyline fill="none" stroke="#BA7517" stroke-width="2.5" points="0,8 20,12 35,10 55,16 70,14 90,20 100,22"/>
                    </svg>
                </div>

            </div>

            <!-- ── Filters ── -->
            <div class="bg-white dark:bg-[#111827] border border-slate-100 dark:border-white/5 rounded-2xl p-4 mb-5">
                <div class="flex flex-col lg:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input
                            type="text"
                            v-model="filterForm.search"
                            @input="applyFilters"
                            placeholder="ابحث باسم الموكل أو اسم الخصم أو رقم القضية أو المحكمة..."
                            class="filter-input pr-9"
                        />
                    </div>
                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            <input type="date" v-model="filterForm.from" @change="applyFilters" class="filter-input pr-9 w-full sm:w-auto" />
                        </div>
                        <span class="text-slate-400 text-xs font-black">إلى</span>
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            <input type="date" v-model="filterForm.to" @change="applyFilters" class="filter-input pr-9 w-full sm:w-auto" />
                        </div>
                        <button @click="resetFilters"
                            class="px-4 py-2.5 rounded-xl text-xs font-black border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-all whitespace-nowrap">
                            <i class="fas fa-times ml-1 text-[10px]"></i> مسح
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════ -->
            <!-- ── Desktop Table (hidden on mobile) ── -->
            <!-- ══════════════════════════════════════════════════════ -->
            <div class="hidden sm:block bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden w-full">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-sm" style="min-width:780px">
                        <thead class="bg-slate-50 dark:bg-black/20 border-b border-slate-100 dark:border-white/5">
                            <tr>
                                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">تاريخ الجلسة</th>
                                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الموكل / القضية</th>
                                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">المحكمة / النوع</th>
                                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الخصم</th>
                                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الجلسة القادمة</th>
                                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">النتيجة</th>
                                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">العمليات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                            <tr v-for="session in sessionsData" :key="session.id"
                                class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all">

                                <!-- تاريخ الجلسة -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-gavel text-xs text-[#854F0B] dark:text-amber-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-700 dark:text-white">{{ formatDate(session.session_date) }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">تاريخ الجلسة</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- الموكل / القضية -->
                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-slate-700 dark:text-white">{{ session.case?.client?.name }}</p>
                                    <Link :href="`/cases/${session.case_id}`"
                                        class="text-xs font-bold text-blue-500 hover:text-[#d4af37] transition-colors inline-flex items-center gap-1 mt-0.5">
                                        {{ session.case?.case_number }}
                                        <i class="fas fa-external-link-alt text-[9px]"></i>
                                    </Link>
                                </td>

                                <!-- المحكمة / النوع -->
                                <td class="px-5 py-5">
                                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ session.case?.court }}</p>
                                    <span class="inline-block mt-0.5 text-[9px] font-black px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400">
                                        {{ session.case?.case_type }}
                                    </span>
                                </td>

                                <!-- الخصم -->
                                <td class="px-5 py-4">
                                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ session.case?.opponent || '---' }}</p>
                                </td>

                                <!-- الجلسة القادمة -->
                                <td class="px-5 py-4">
                                    <span v-if="session.next_session"
                                        class="inline-flex items-center gap-1.5 text-[10px] font-black bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/20 px-2.5 py-1 rounded-lg whitespace-nowrap">
                                        <i class="far fa-calendar-alt text-[10px]"></i>
                                        {{ formatDate(session.next_session) }}
                                    </span>
                                    <span v-else class="text-[10px] font-bold text-slate-400 italic">لم يحدد</span>
                                </td>

                                <td class="px-5 py-4" style="max-width:180px">
                                    <template v-if="session.session_result">
                                        <span
                                            class="inline-block text-[10px] font-black px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20"
                                            :class="{ 'line-clamp-2': !expandedResults.has(session.id) }"
                                        >
                                            {{ session.session_result }}
                                        </span>
                                        <button
                                            v-if="session.session_result.length > 60"
                                            @click="toggleResult(session.id)"
                                            class="text-[9px] font-black text-blue-500 hover:text-blue-700 mt-1 block transition-colors"
                                        >
                                            {{ expandedResults.has(session.id) ? 'عرض أقل ▲' : 'عرض المزيد ▼' }}
                                        </button>
                                    </template>
                                    <span v-else class="text-[10px] font-bold text-slate-400 italic">في انتظار النتيجة...</span>
                                </td>

                                <!-- العمليات -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <Link :href="`/cases/${session.case_id}`"
                                            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all"
                                            title="تفاصيل القضية">
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                        </Link>
                                        <button @click="openEditModal(session)"
                                            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 transition-all"
                                            title="تعديل">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>
                                        <button @click="openDeleteConfirm(session)"
                                            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all"
                                            title="حذف">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>

                            <!-- Empty State -->
                            <tr v-if="!sessionsData.length">
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <i class="fas fa-folder-open text-3xl text-slate-200 dark:text-slate-700 mb-3 block"></i>
                                    <p class="text-sm font-bold text-slate-400">لا توجد جلسات مطابقة</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="sessions.last_page > 1"
                    class="px-5 py-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-black/10">
                    <p class="text-xs font-bold text-slate-400">
                        عرض {{ sessions.from }}–{{ sessions.to }} من أصل {{ sessions.total }} جلسة
                    </p>
                    <div class="flex items-center gap-1.5">
                        <Link v-if="sessions.prev_page_url" :href="sessions.prev_page_url" preserve-state
                            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
                            <i class="fas fa-chevron-right"></i>
                        </Link>
                        <span class="px-3 py-1.5 text-xs font-black text-slate-600 dark:text-white">
                            {{ sessions.current_page }} / {{ sessions.last_page }}
                        </span>
                        <Link v-if="sessions.next_page_url" :href="sessions.next_page_url" preserve-state
                            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
                            <i class="fas fa-chevron-left"></i>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════ -->
            <!-- ── Mobile Cards (shown only on mobile) ── -->
            <!-- ══════════════════════════════════════════════════════ -->
            <div class="sm:hidden space-y-3">

                <!-- Empty State -->
                <div v-if="!sessionsData.length" class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 px-6 py-16 text-center">
                    <i class="fas fa-folder-open text-3xl text-slate-200 dark:text-slate-700 mb-3 block"></i>
                    <p class="text-sm font-bold text-slate-400">لا توجد جلسات مطابقة</p>
                </div>

                <div v-for="session in sessionsData" :key="'m-' + session.id"
                    class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 p-4">

                    <!-- Card Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0 ml-3">
                            <p class="text-sm font-black text-slate-800 dark:text-white truncate">{{ session.case?.client?.name }}</p>
                            <Link :href="`/cases/${session.case_id}`"
                                class="text-xs font-bold text-blue-500 hover:text-[#d4af37] transition-colors inline-flex items-center gap-1 mt-0.5">
                                {{ session.case?.case_number }}
                                <i class="fas fa-external-link-alt text-[9px]"></i>
                            </Link>
                        </div>
                        <div class="flex items-center gap-1.5 bg-[#FAEEDA] dark:bg-amber-500/10 px-2.5 py-1.5 rounded-lg flex-shrink-0">
                            <i class="fas fa-gavel text-[10px] text-[#854F0B] dark:text-amber-400"></i>
                            <span class="text-[10px] font-black text-[#633806] dark:text-amber-300 whitespace-nowrap">{{ formatDate(session.session_date) }}</span>
                        </div>
                    </div>

                    <!-- Card Info Grid -->
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="bg-slate-50 dark:bg-white/[0.03] rounded-xl p-2.5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">المحكمة</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 leading-tight">{{ session.case?.court }}</p>
                            <span class="inline-block mt-1 text-[8px] font-black px-1.5 py-0.5 rounded-full bg-white dark:bg-white/5 text-slate-500 border border-slate-200 dark:border-white/10">
                                {{ session.case?.case_type }}
                            </span>
                        </div>
                        <div class="bg-slate-50 dark:bg-white/[0.03] rounded-xl p-2.5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">الخصم</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 leading-tight">{{ session.case?.opponent || '---' }}</p>
                        </div>
                    </div>

                    <!-- Next Session -->
                    <div class="mb-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">الجلسة القادمة</p>
                        <span v-if="session.next_session"
                            class="inline-flex items-center gap-1.5 text-[10px] font-black bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/20 px-2.5 py-1 rounded-lg">
                            <i class="far fa-calendar-alt text-[10px]"></i>
                            {{ formatDate(session.next_session) }}
                        </span>
                        <span v-else class="text-[10px] font-bold text-slate-400 italic">لم يحدد</span>
                    </div>

                    <!-- Result -->
                    <div class="mb-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">النتيجة</p>
                        <template v-if="session.session_result">
                            <span
                                class="inline-block text-[10px] font-black px-2.5 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 w-full leading-relaxed"
                                :class="{ 'line-clamp-2': !expandedResults.has('m-' + session.id) }"
                            >
                                {{ session.session_result }}
                            </span>
                            <button
                                v-if="session.session_result.length > 60"
                                @click="toggleResult('m-' + session.id)"
                                class="text-[9px] font-black text-blue-500 hover:text-blue-700 mt-1 block transition-colors"
                            >
                                {{ expandedResults.has('m-' + session.id) ? 'عرض أقل ▲' : 'عرض المزيد ▼' }}
                            </button>
                        </template>
                        <span v-else class="text-[10px] font-bold text-slate-400 italic">في انتظار النتيجة...</span>
                    </div>

                    <!-- Card Actions -->
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-white/5">
                        <Link :href="`/cases/${session.case_id}`"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all">
                            <i class="fas fa-external-link-alt text-[9px]"></i>
                            القضية
                        </Link>
                        <button @click="openEditModal(session)"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-500 hover:text-blue-500 hover:border-blue-300 transition-all">
                            <i class="fas fa-pen text-[9px]"></i>
                            تعديل
                        </button>
                        <button @click="openDeleteConfirm(session)"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-500 hover:text-red-500 hover:border-red-300 transition-all">
                            <i class="fas fa-trash text-[9px]"></i>
                            حذف
                        </button>
                    </div>
                </div>

                <!-- Mobile Pagination -->
                <div v-if="sessions.last_page > 1"
                    class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 px-4 py-3 flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-400">
                        {{ sessions.from }}–{{ sessions.to }} من {{ sessions.total }}
                    </p>
                    <div class="flex items-center gap-1.5">
                        <Link v-if="sessions.prev_page_url" :href="sessions.prev_page_url" preserve-state
                            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] transition-all text-xs">
                            <i class="fas fa-chevron-right"></i>
                        </Link>
                        <span class="px-2 text-xs font-black text-slate-600 dark:text-white">
                            {{ sessions.current_page }} / {{ sessions.last_page }}
                        </span>
                        <Link v-if="sessions.next_page_url" :href="sessions.next_page_url" preserve-state
                            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] transition-all text-xs">
                            <i class="fas fa-chevron-left"></i>
                        </Link>
                    </div>
                </div>

            </div>
            <!-- ── End Mobile Cards ── -->

        </div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- Modal: تعديل الجلسة -->
        <!-- ═══════════════════════════════════════════ -->
        <div v-if="editModal.show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-[#111827] w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-black/20">
                    <div>
                        <h3 class="font-black text-base text-slate-800 dark:text-white">تعديل بيانات الجلسة</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-0.5">
                            {{ editModal.session?.case?.client?.name }} — {{ editModal.session?.case?.case_number }}
                        </p>
                    </div>
                    <button @click="editModal.show = false"
                        class="w-7 h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 transition-all">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <form @submit.prevent="submitEdit" class="p-6 space-y-4 text-right">
                    <div>
                        <label class="form-label">تاريخ الجلسة *</label>
                        <input v-model="sessionForm.session_date" type="date" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">نتيجة الجلسة</label>
                        <textarea v-model="sessionForm.session_result" rows="3" class="form-input resize-none"
                            placeholder="اكتب ما تم في الجلسة..."></textarea>
                    </div>
                    <div>
                        <label class="form-label">موعد الجلسة القادمة</label>
                        <input v-model="sessionForm.next_session" type="date" class="form-input">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="editModal.show = false"
                            class="flex-1 py-3 text-xs font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
                            إلغاء
                        </button>
                        <button type="submit" :disabled="sessionForm.processing"
                            class="flex-[2] py-3 bg-[#d4af37] text-[#1e272e] font-black rounded-xl hover:bg-[#b8962d] transition-all flex items-center justify-center gap-2 disabled:opacity-60">
                            <i v-if="sessionForm.processing" class="fas fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fas fa-save text-xs"></i>
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="deleteConfirm.show" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-[#111827] w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">

                <div class="px-6 pt-8 pb-5 text-center">
                    <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-500/10 border-4 border-red-100 dark:border-red-500/20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trash-alt text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-base font-black text-slate-800 dark:text-white mb-2">تأكيد الحذف</h3>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed">
                        هل أنت متأكد من حذف جلسة
                        <span class="font-black text-slate-700 dark:text-slate-200">{{ formatDate(deleteConfirm.session?.session_date) }}</span>
                        للموكل
                        <span class="font-black text-slate-700 dark:text-slate-200">{{ deleteConfirm.session?.case?.client?.name }}</span>
                        ؟
                    </p>
                    <p class="text-[10px] font-black text-red-500 mt-2">لا يمكن التراجع عن هذا الإجراء.</p>
                </div>

                <div class="flex gap-3 px-6 pb-6">
                    <button @click="deleteConfirm.show = false"
                        class="flex-1 py-2.5 text-xs font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
                        إلغاء
                    </button>
                    <button @click="confirmDelete" :disabled="deleteConfirm.processing"
                        class="flex-1 py-2.5 text-xs font-black text-white bg-red-500 rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2 disabled:opacity-60">
                        <i v-if="deleteConfirm.processing" class="fas fa-spinner fa-spin text-xs"></i>
                        <i v-else class="fas fa-trash text-xs"></i>
                        نعم، احذف
                    </button>
                </div>

            </div>
        </div>

    </DashboardLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { toast } from 'vue-sonner'

const props = defineProps({
    sessions: Object, // Laravel Paginated Object
})

// ── State ────────────────────────────────────────────────────────────────────
const search          = ref('')
const filter          = reactive({ from: '', to: '' })
const expandedResults = ref(new Set())   // tracks expanded "عرض المزيد" rows

// ── Edit Modal ────────────────────────────────────────────────────────────────
const editModal = reactive({ show: false, session: null })

// ── Delete Confirm ────────────────────────────────────────────────────────────
const deleteConfirm = reactive({ show: false, session: null, processing: false })

// ── Form ──────────────────────────────────────────────────────────────────────
const sessionForm = useForm({
    session_date:   '',
    session_result: '',
    next_session:   '',
})

const filterForm = useForm({
    search: '',
    from: '',
    to: '',
})


const sessionsData = computed(() => props.sessions?.data || [])

const completedSessions = computed(() =>
    sessionsData.value.filter(s => s.session_result).length
)

const pendingSessions = computed(() =>
    sessionsData.value.filter(s => !s.session_result).length
)

let timeout = null

const applyFilters = () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        filterForm.get('/sessions', {
            preserveState: true,
            replace: true,
        })
    }, 400)
}

// ── Methods ───────────────────────────────────────────────────────────────────
const formatDate = (date) => {
    if (!date) return '---'
    return new Date(date).toLocaleDateString('ar-EG', {
        year: 'numeric', month: 'short', day: 'numeric',
    })
}

const resetFilters = () => {
    filterForm.search = ''
    filterForm.from   = ''
    filterForm.to     = ''

    applyFilters()
}

/** Toggle "عرض المزيد / عرض أقل" for a given key (id or 'm-'+id) */
const toggleResult = (key) => {
    const s = new Set(expandedResults.value)
    s.has(key) ? s.delete(key) : s.add(key)
    expandedResults.value = s
}

const openEditModal = (session) => {
    editModal.session          = session
    sessionForm.session_date   = session.session_date   || ''
    sessionForm.session_result = session.session_result || ''
    sessionForm.next_session   = session.next_session   || ''
    editModal.show             = true
}

const submitEdit = () => {
    sessionForm.put(`/sessions/${editModal.session.id}`, {
        onSuccess: () => {
            editModal.show = false
            toast.success('تم تعديل بيانات الجلسة بنجاح')
        },
        onError: () => toast.error('حدث خطأ، يرجى مراجعة البيانات'),
    })
}

const openDeleteConfirm = (session) => {
    deleteConfirm.session    = session
    deleteConfirm.show       = true
    deleteConfirm.processing = false
}

const confirmDelete = () => {
    deleteConfirm.processing = true
    router.delete(`/sessions/${deleteConfirm.session.id}`, {
        onSuccess: () => {
            deleteConfirm.show       = false
            deleteConfirm.processing = false
            toast.success('تم حذف الجلسة بنجاح')
        },
        onError: () => {
            deleteConfirm.processing = false
            toast.error('حدث خطأ أثناء الحذف')
        },
    })
}
</script>

<style scoped>
/* ── Filter Inputs ────────────────────────────────────────────────────────── */
.filter-input {
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 0.625rem;
    padding: 0.625rem 0.875rem;
    font-size: 0.8rem;
    font-weight: 700;
    outline: none;
    transition: all 0.2s;
    color: #1e293b;
    width: 100%;
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
:where(.dark) .filter-input:focus {
    border-color: #d4af37;
}

/* ── Form Inputs (Modal) ─────────────────────────────────────────────────── */
.form-label {
    display: block;
    font-size: 10px;
    font-weight: 900;
    color: #94a3b8;
    text-transform: uppercase;
    margin-bottom: 0.375rem;
    letter-spacing: 0.1em;
}
.form-input {
    width: 100%;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 0.625rem;
    padding: 0.75rem 0.875rem;
    font-size: 0.875rem;
    font-weight: 700;
    outline: none;
    transition: all 0.2s;
    color: #1e293b;
}
.form-input:focus {
    border-color: #d4af37;
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}
:where(.dark) .form-input {
    background-color: rgba(0, 0, 0, 0.2);
    border-color: rgba(255, 255, 255, 0.08);
    color: white;
}
:where(.dark) .form-input:focus {
    border-color: #d4af37;
    background-color: rgba(0, 0, 0, 0.3);
}

/* ── Stats Cards ─────────────────────────────────────────────────────────── */

/* Total */
.stat-card.stat-total {
    background-color: #EEEDFE;
    border-color: rgba(127, 119, 221, 0.4);
}
:where(.dark) .stat-card.stat-total {
    background-color: rgba(83, 74, 183, 0.08);
    border-color: rgba(127, 119, 221, 0.2);
}
.stat-total-num   { color: #3C3489; }
.stat-total-label { color: #534AB7; }
:where(.dark) .stat-total-num   { color: #AFA9EC; }
:where(.dark) .stat-total-label { color: #7F77DD; }

.stat-total-icon {
    background-color: rgba(83, 74, 183, 0.15);
    color: #534AB7;
}
:where(.dark) .stat-total-icon {
    background-color: rgba(83, 74, 183, 0.2);
    color: #AFA9EC;
}

.ai-badge-total {
    background-color: rgba(83, 74, 183, 0.15);
    color: #3C3489;
}
:where(.dark) .ai-badge-total {
    background-color: rgba(83, 74, 183, 0.2);
    color: #AFA9EC;
}
.ai-pulse-total {
    background-color: #7F77DD;
    animation: pulse-anim 2s ease-in-out infinite;
}

/* Completed */
.stat-card.stat-completed {
    background-color: #EAF3DE;
    border-color: rgba(99, 153, 34, 0.4);
}
:where(.dark) .stat-card.stat-completed {
    background-color: rgba(59, 109, 17, 0.08);
    border-color: rgba(99, 153, 34, 0.2);
}
.stat-completed-num   { color: #27500A; }
.stat-completed-label { color: #3B6D11; }
:where(.dark) .stat-completed-num   { color: #97C459; }
:where(.dark) .stat-completed-label { color: #639922; }

.stat-completed-icon {
    background-color: rgba(59, 109, 17, 0.15);
    color: #3B6D11;
}
:where(.dark) .stat-completed-icon {
    background-color: rgba(59, 109, 17, 0.2);
    color: #97C459;
}
.ai-badge-completed {
    background-color: rgba(59, 109, 17, 0.15);
    color: #27500A;
}
:where(.dark) .ai-badge-completed {
    background-color: rgba(59, 109, 17, 0.2);
    color: #97C459;
}
.ai-pulse-completed {
    background-color: #639922;
    animation: pulse-anim 2s ease-in-out infinite;
    animation-delay: 0.3s;
}

/* Pending */
.stat-card.stat-pending {
    background-color: #FAEEDA;
    border-color: rgba(186, 117, 23, 0.4);
}
:where(.dark) .stat-card.stat-pending {
    background-color: rgba(133, 79, 11, 0.08);
    border-color: rgba(186, 117, 23, 0.2);
}
.stat-pending-num   { color: #633806; }
.stat-pending-label { color: #854F0B; }
:where(.dark) .stat-pending-num   { color: #EF9F27; }
:where(.dark) .stat-pending-label { color: #BA7517; }

.stat-pending-icon {
    background-color: rgba(133, 79, 11, 0.15);
    color: #854F0B;
}
:where(.dark) .stat-pending-icon {
    background-color: rgba(133, 79, 11, 0.2);
    color: #EF9F27;
}
.ai-badge-pending {
    background-color: rgba(133, 79, 11, 0.15);
    color: #633806;
}
:where(.dark) .ai-badge-pending {
    background-color: rgba(133, 79, 11, 0.2);
    color: #EF9F27;
}
.ai-pulse-pending {
    background-color: #BA7517;
    animation: pulse-anim 2s ease-in-out infinite;
    animation-delay: 0.6s;
}

@keyframes pulse-anim {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.4; transform: scale(0.8); }
}
</style>
