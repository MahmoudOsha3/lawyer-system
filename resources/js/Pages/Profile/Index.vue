<template>
  <Head title="الإعدادات" />

  <DashboardLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-0 pb-16 pt-6">

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">الإعدادات</h1>
        <p class="text-xs font-bold text-slate-400 mt-1">إدارة بيانات المكتب والحساب الشخصي</p>
      </div>

      <!-- Tabs -->
      <div class="flex gap-1 bg-slate-100 dark:bg-white/[0.04] p-1 rounded-xl mb-8 w-fit">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="activeTab = tab.key"
          :class="activeTab === tab.key
            ? 'bg-white dark:bg-[#1e293b] text-slate-800 dark:text-white shadow-sm'
            : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
          class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-black transition-all whitespace-nowrap"
        >
          <i :class="tab.icon" class="text-[10px]"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- ══════════════════════════════
           TAB 1: بيانات المكتب
      ══════════════════════════════ -->
      <div v-if="activeTab === 'office'">
        <form @submit.prevent="saveOffice" class="space-y-6">

          <div class="settings-card">
            <div class="settings-card-header">
              <i class="fas fa-building text-[#d4af37]"></i>
              <h2 class="settings-card-title">بيانات المكتب الأساسية</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="md:col-span-2">
                <label class="form-label">اسم المكتب *</label>
                <input v-model="officeForm.office_name" type="text" class="form-input" placeholder="مثال: مكتب محمود عثمان للمحاماة" required />
                <p v-if="officeForm.errors.office_name" class="err-msg">{{ officeForm.errors.office_name }}</p>
              </div>

              <div>
                <label class="form-label">تليفون المكتب</label>
                <input v-model="officeForm.office_phone" type="text" class="form-input" placeholder="02-XXXXXXXX" />
                <p v-if="officeForm.errors.office_phone" class="err-msg">{{ officeForm.errors.office_phone }}</p>
              </div>

              <div>
                <label class="form-label">الموبايل</label>
                <input v-model="officeForm.mobile" type="text" class="form-input" placeholder="01XXXXXXXXX" />
                <p v-if="officeForm.errors.mobile" class="err-msg">{{ officeForm.errors.mobile }}</p>
              </div>

              <div class="md:col-span-2">
                <label class="form-label">عنوان المكتب</label>
                <input v-model="officeForm.address" type="text" class="form-input" placeholder="الشارع، الحي، المدينة" />
                <p v-if="officeForm.errors.address" class="err-msg">{{ officeForm.errors.address }}</p>
              </div>

              <div>
                <label class="form-label">ساعات العمل</label>
                <input v-model="officeForm.working_hours" type="text" class="form-input" placeholder="مثال: 4 عصراً – 11 مساءً" />
                <p v-if="officeForm.errors.working_hours" class="err-msg">{{ officeForm.errors.working_hours }}</p>
              </div>

              <div>
                <label class="form-label">الرقم الضريبي</label>
                <input v-model="officeForm.tax_number" type="text" class="form-input font-mono" placeholder="XXX-XXX-XXX" />
                <p v-if="officeForm.errors.tax_number" class="err-msg">{{ officeForm.errors.tax_number }}</p>
              </div>

              <div>
                <label class="form-label">البريد الإلكتروني للمكتب</label>
                <input v-model="officeForm.office_email" type="email" class="form-input" placeholder="office@example.com" />
                <p v-if="officeForm.errors.office_email" class="err-msg">{{ officeForm.errors.office_email }}</p>
              </div>

              <div>
                <label class="form-label">الموقع الإلكتروني</label>
                <input v-model="officeForm.website" type="url" class="form-input" placeholder="https://example.com" />
                <p v-if="officeForm.errors.website" class="err-msg">{{ officeForm.errors.website }}</p>
              </div>
            </div>
          </div>

          <!-- بيانات القيد النقابي -->
          <div class="settings-card">
            <div class="settings-card-header">
              <i class="fas fa-id-card text-[#d4af37]"></i>
              <h2 class="settings-card-title">بيانات القيد النقابي</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label">رقم القيد</label>
                <input v-model="officeForm.bar_number" type="text" class="form-input font-mono" placeholder="XXXXXXXX" />
                <p v-if="officeForm.errors.bar_number" class="err-msg">{{ officeForm.errors.bar_number }}</p>
              </div>

              <div>
                <label class="form-label">تاريخ القيد</label>
                <input v-model="officeForm.bar_date" type="date" class="form-input" />
                <p v-if="officeForm.errors.bar_date" class="err-msg">{{ officeForm.errors.bar_date }}</p>
              </div>

              <div>
                <label class="form-label">درجة القيد</label>
                <select v-model="officeForm.bar_degree" class="form-input appearance-none">
                  <option value="">-- اختيار الدرجة --</option>
                  <option value="ابتدائي">محامي ابتدائي</option>
                  <option value="استئناف">محامي استئناف</option>
                  <option value="نقض">محامي أمام محكمة النقض</option>
                  <option value="استئناف_عالي">استئناف عالٍ ومجلس الدولة</option>
                </select>
                <p v-if="officeForm.errors.bar_degree" class="err-msg">{{ officeForm.errors.bar_degree }}</p>
              </div>

              <div>
                <label class="form-label">حالة القيد</label>
                <select v-model="officeForm.bar_status" class="form-input appearance-none">
                  <option value="مشتغل">مشتغل</option>
                  <option value="غير_مشتغل">غير مشتغل</option>
                </select>
              </div>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit" :disabled="officeForm.processing" class="save-btn">
              <i v-if="officeForm.processing" class="fas fa-spinner fa-spin text-xs"></i>
              <i v-else class="fas fa-save text-xs"></i>
              حفظ بيانات المكتب
            </button>
          </div>

        </form>
      </div>

      <!-- ══════════════════════════════
           TAB 2: البيانات الشخصية
      ══════════════════════════════ -->
      <div v-if="activeTab === 'personal'">
        <form @submit.prevent="savePersonal" class="space-y-6">

          <div class="settings-card">
            <div class="settings-card-header">
              <i class="fas fa-user text-[#d4af37]"></i>
              <h2 class="settings-card-title">البيانات الشخصية</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="md:col-span-2">
                <label class="form-label">الاسم الكامل *</label>
                <input v-model="personalForm.name" type="text" class="form-input" placeholder="الاسم كما هو في الهوية" required />
                <p v-if="personalForm.errors.name" class="err-msg">{{ personalForm.errors.name }}</p>
              </div>

              <div>
                <label class="form-label">البريد الإلكتروني *</label>
                <input v-model="personalForm.email" type="email" class="form-input" required />
                <p v-if="personalForm.errors.email" class="err-msg">{{ personalForm.errors.email }}</p>
              </div>

              <div>
                <label class="form-label">رقم الهاتف</label>
                <input v-model="personalForm.phone" type="text" class="form-input" placeholder="01XXXXXXXXX" />
                <p v-if="personalForm.errors.phone" class="err-msg">{{ personalForm.errors.phone }}</p>
              </div>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit" :disabled="personalForm.processing" class="save-btn">
              <i v-if="personalForm.processing" class="fas fa-spinner fa-spin text-xs"></i>
              <i v-else class="fas fa-save text-xs"></i>
              حفظ البيانات الشخصية
            </button>
          </div>

        </form>
      </div>

      <!-- ══════════════════════════════
           TAB 3: كلمة السر
      ══════════════════════════════ -->
      <div v-if="activeTab === 'password'">
        <form @submit.prevent="savePassword" class="space-y-6">

          <div class="settings-card">
            <div class="settings-card-header">
              <i class="fas fa-lock text-[#d4af37]"></i>
              <h2 class="settings-card-title">تغيير كلمة المرور</h2>
            </div>

            <div class="max-w-md space-y-5">

              <div>
                <label class="form-label">كلمة المرور الحالية *</label>
                <div class="relative">
                  <input
                    v-model="passwordForm.current_password"
                    :type="showPasswords.current ? 'text' : 'password'"
                    class="form-input pl-10"
                    placeholder="••••••••"
                    required
                  />
                  <button type="button" @click="showPasswords.current = !showPasswords.current"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                    <i :class="showPasswords.current ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                  </button>
                </div>
                <p v-if="passwordForm.errors.current_password" class="err-msg">{{ passwordForm.errors.current_password }}</p>
              </div>

              <div>
                <label class="form-label">كلمة المرور الجديدة *</label>
                <div class="relative">
                  <input
                    v-model="passwordForm.password"
                    :type="showPasswords.new ? 'text' : 'password'"
                    class="form-input pl-10"
                    placeholder="••••••••"
                    required
                  />
                  <button type="button" @click="showPasswords.new = !showPasswords.new"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                    <i :class="showPasswords.new ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                  </button>
                </div>
                <p v-if="passwordForm.errors.password" class="err-msg">{{ passwordForm.errors.password }}</p>
                <!-- Strength indicator -->
                <div v-if="passwordForm.password" class="mt-2 flex gap-1">
                  <div v-for="i in 4" :key="i"
                    :class="passwordStrength >= i ? strengthColor : 'bg-slate-200 dark:bg-white/10'"
                    class="h-1 flex-1 rounded-full transition-all duration-300">
                  </div>
                </div>
                <p v-if="passwordForm.password" class="text-[10px] font-bold mt-1" :class="strengthTextColor">
                  {{ strengthLabel }}
                </p>
              </div>

              <div>
                <label class="form-label">تأكيد كلمة المرور الجديدة *</label>
                <div class="relative">
                  <input
                    v-model="passwordForm.password_confirmation"
                    :type="showPasswords.confirm ? 'text' : 'password'"
                    class="form-input pl-10"
                    :class="passwordForm.password_confirmation && passwordForm.password !== passwordForm.password_confirmation
                      ? 'border-red-400 focus:border-red-400'
                      : passwordForm.password_confirmation && passwordForm.password === passwordForm.password_confirmation
                      ? 'border-emerald-400 focus:border-emerald-400' : ''"
                    placeholder="••••••••"
                    required
                  />
                  <button type="button" @click="showPasswords.confirm = !showPasswords.confirm"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                    <i :class="showPasswords.confirm ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                  </button>
                </div>
                <p v-if="passwordForm.password_confirmation && passwordForm.password !== passwordForm.password_confirmation"
                  class="err-msg">كلمتا المرور غير متطابقتين</p>
                <p v-if="passwordForm.password_confirmation && passwordForm.password === passwordForm.password_confirmation"
                  class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
                  <i class="fas fa-check-circle text-[9px]"></i> كلمتا المرور متطابقتان
                </p>
                <p v-if="passwordForm.errors.password_confirmation" class="err-msg">{{ passwordForm.errors.password_confirmation }}</p>
              </div>

              <!-- تلميحات -->
              <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 rounded-xl p-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">متطلبات كلمة المرور</p>
                <ul class="space-y-1.5">
                  <li v-for="req in passwordRequirements" :key="req.label"
                    class="flex items-center gap-2 text-[11px] font-bold"
                    :class="req.met ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400'">
                    <i :class="req.met ? 'fas fa-check-circle' : 'far fa-circle'" class="text-[10px]"></i>
                    {{ req.label }}
                  </li>
                </ul>
              </div>

            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit"
              :disabled="passwordForm.processing || passwordForm.password !== passwordForm.password_confirmation"
              class="save-btn">
              <i v-if="passwordForm.processing" class="fas fa-spinner fa-spin text-xs"></i>
              <i v-else class="fas fa-lock text-xs"></i>
              تحديث كلمة المرور
            </button>
          </div>

        </form>
      </div>

    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { toast } from 'vue-sonner'

// ── Props ──────────────────────────────────────────
const props = defineProps({
  settings: Object, // بيانات المكتب المحفوظة
  user:     Object, // بيانات المستخدم الحالي
})

// ── Tabs ───────────────────────────────────────────
const activeTab = ref('office')
const tabs = [
  { key: 'office',   label: 'بيانات المكتب',    icon: 'fas fa-building'  },
  { key: 'personal', label: 'البيانات الشخصية',  icon: 'fas fa-user'      },
  { key: 'password', label: 'كلمة المرور',        icon: 'fas fa-lock'      },
]

// ── Forms ──────────────────────────────────────────

// بيانات المكتب
const officeForm = useForm({
  office_name:   props.settings?.office_name   || '',
  office_phone:  props.settings?.office_phone  || '',
  mobile:        props.settings?.mobile        || '',
  address:       props.settings?.address       || '',
  working_hours: props.settings?.working_hours || '',
  tax_number:    props.settings?.tax_number    || '',
  office_email:  props.settings?.office_email  || '',
  website:       props.settings?.website       || '',
  bar_number:    props.settings?.bar_number    || '',
  bar_date:      props.settings?.bar_date      || '',
  bar_degree:    props.settings?.bar_degree    || '',
  bar_status:    props.settings?.bar_status    || 'مشتغل',
})

const saveOffice = () => {
  officeForm.post(route('settings.office'), {
    onSuccess: () => toast.success('تم حفظ بيانات المكتب بنجاح'),
    onError:   () => toast.error('حدث خطأ أثناء الحفظ'),
  })
}

// البيانات الشخصية
const personalForm = useForm({
  name:  props.user?.name  || '',
  email: props.user?.email || '',
  phone: props.user?.phone || '',
})

const savePersonal = () => {
  personalForm.put(route('settings.personal'), {
    onSuccess: () => toast.success('تم تحديث البيانات الشخصية بنجاح'),
    onError:   () => toast.error('حدث خطأ أثناء الحفظ'),
  })
}

// كلمة المرور
const passwordForm = useForm({
  current_password:      '',
  password:              '',
  password_confirmation: '',
})

const showPasswords = reactive({ current: false, new: false, confirm: false })

const savePassword = () => {
  passwordForm.put(route('settings.password'), {
    onSuccess: () => {
      toast.success('تم تغيير كلمة المرور بنجاح')
      passwordForm.reset()
    },
    onError: () => toast.error('تحقق من البيانات المدخلة'),
  })
}

// ── Password Strength ──────────────────────────────
const passwordStrength = computed(() => {
  const p = passwordForm.password
  if (!p) return 0
  let score = 0
  if (p.length >= 8)          score++
  if (/[A-Z]/.test(p))        score++
  if (/[0-9]/.test(p))        score++
  if (/[^A-Za-z0-9]/.test(p)) score++
  return score
})

const strengthColor = computed(() => {
  const s = passwordStrength.value
  if (s <= 1) return 'bg-red-400'
  if (s === 2) return 'bg-amber-400'
  if (s === 3) return 'bg-yellow-400'
  return 'bg-emerald-500'
})

const strengthTextColor = computed(() => {
  const s = passwordStrength.value
  if (s <= 1) return 'text-red-500'
  if (s === 2) return 'text-amber-500'
  if (s === 3) return 'text-yellow-600'
  return 'text-emerald-600 dark:text-emerald-400'
})

const strengthLabel = computed(() => {
  const s = passwordStrength.value
  if (s <= 1) return 'ضعيفة جداً'
  if (s === 2) return 'ضعيفة'
  if (s === 3) return 'متوسطة'
  return 'قوية'
})

const passwordRequirements = computed(() => {
  const p = passwordForm.password
  return [
    { label: '8 أحرف على الأقل',               met: p.length >= 8          },
    { label: 'حرف كبير (A-Z)',                   met: /[A-Z]/.test(p)        },
    { label: 'رقم واحد على الأقل',               met: /[0-9]/.test(p)        },
    { label: 'رمز خاص (!@#$...)',               met: /[^A-Za-z0-9]/.test(p) },
  ]
})
</script>

<style scoped>
/* ── Card ── */
.settings-card {
  background: white;
  border: 1px solid #f1f5f9;
  border-radius: 1.25rem;
  padding: 1.5rem;
}
:where(.dark) .settings-card {
  background: #111827;
  border-color: rgba(255,255,255,0.05);
}

.settings-card-header {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #f1f5f9;
}
:where(.dark) .settings-card-header { border-color: rgba(255,255,255,0.05); }

.settings-card-title {
  font-size: 0.9rem;
  font-weight: 900;
  color: #1e293b;
}
:where(.dark) .settings-card-title { color: white; }

/* ── Form ── */
.form-label {
  display: block;
  font-size: 10px;
  font-weight: 900;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  margin-bottom: 0.375rem;
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
  box-shadow: 0 0 0 3px rgba(212,175,55,0.1);
}
:where(.dark) .form-input {
  background-color: rgba(0,0,0,0.2);
  border-color: rgba(255,255,255,0.08);
  color: white;
}
:where(.dark) .form-input:focus {
  border-color: #d4af37;
  background-color: rgba(0,0,0,0.3);
}

.err-msg {
  font-size: 10px;
  font-weight: 700;
  color: #ef4444;
  margin-top: 4px;
}

/* ── Save Button ── */
.save-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #d4af37;
  color: #1e272e;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 0.8rem;
  font-weight: 900;
  transition: all 0.2s;
  box-shadow: 0 4px 14px rgba(212,175,55,0.25);
}
.save-btn:hover:not(:disabled) { background-color: #b8962d; }
.save-btn:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
