<template>
  <Head title="تسجيل الدخول | مكتب العدالة" />

  <div class="min-h-screen bg-[#f5f3ee] flex" dir="rtl">

    <!-- ── Left: Branding Panel ── -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-between p-14 bg-white border-l border-[#ece8df]">

      <!-- Background geometric pattern -->
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0" style="background:radial-gradient(ellipse at 30% 40%, rgba(180,148,40,0.06) 0%, transparent 60%)"></div>
        <!-- Circles -->
        <div class="absolute top-[-80px] right-[-80px] w-[400px] h-[400px] rounded-full border border-[#b89428]/10"></div>
        <div class="absolute top-[-40px] right-[-40px] w-[280px] h-[280px] rounded-full border border-[#b89428]/8"></div>
        <div class="absolute bottom-[-100px] left-[-60px] w-[350px] h-[350px] rounded-full border border-[#b89428]/8"></div>
        <!-- Grid lines -->
        <svg class="absolute inset-0 w-full h-full opacity-[0.035]" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="grid" width="48" height="48" patternUnits="userSpaceOnUse">
              <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#b89428" stroke-width="0.5"/>
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
      </div>

      <!-- Content -->
      <div class="relative z-10">
        <!-- Logo -->
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-[#b89428] rounded-2xl flex items-center justify-center shadow-lg shadow-[#b89428]/25" style="transform:rotate(3deg)">
            <i class="fas fa-balance-scale text-white text-lg"></i>
          </div>
          <div>
            <p class="text-[#1a1814] font-black text-lg leading-none">مكتب العدالة</p>
            <p class="text-[#b89428] text-[10px] font-bold uppercase tracking-[0.25em]">Law Firm Pro</p>
          </div>
        </div>
      </div>

      <!-- Center quote -->
      <div class="relative z-10 flex-1 flex flex-col justify-center">
        <div class="mb-6">
          <div class="w-10 h-[2px] bg-[#b89428] rounded-full mb-6"></div>
          <h2 class="text-[#1a1814] font-black text-3xl leading-[1.25] mb-5" style="font-family:'Georgia',serif">
            نظام إدارة<br>
            <span class="text-[#b89428]">المكتب القانوني</span><br>
            الرقمي المتكامل
          </h2>
          <p class="text-[#888070] text-sm font-medium leading-relaxed max-w-sm">
            إدارة القضايا، الموكلين، الجلسات، والمالية في منصة واحدة متكاملة مصممة لمكتب المحاماة الحديث.
          </p>
        </div>

        <!-- Features -->
        <div class="space-y-4">
          <div v-for="feat in features" :key="feat.text" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-[#b89428]/8 border border-[#b89428]/18 flex items-center justify-center flex-shrink-0">
              <i :class="feat.icon" class="text-[#b89428] text-xs"></i>
            </div>
            <span class="text-[#6b6455] text-sm font-medium">{{ feat.text }}</span>
          </div>
        </div>
      </div>

      <!-- Bottom stats -->
      <div class="relative z-10 grid grid-cols-3 gap-4">
        <div v-for="stat in stats" :key="stat.label" class="bg-[#faf8f4] border border-[#ece8df] rounded-2xl p-4 text-center">
          <p class="text-[#b89428] font-black text-2xl leading-none mb-1" style="font-family:'Georgia',serif">{{ stat.num }}</p>
          <p class="text-[#a89c8a] text-[10px] font-bold uppercase tracking-wider">{{ stat.label }}</p>
        </div>
      </div>

    </div>

    <!-- ── Right: Login Form ── -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-16 bg-[#f5f3ee]">

      <div class="w-full max-w-[420px]">

        <!-- Mobile logo -->
        <div class="flex items-center gap-3 mb-10 lg:hidden">
          <div class="w-10 h-10 bg-[#b89428] rounded-xl flex items-center justify-center" style="transform:rotate(3deg)">
            <i class="fas fa-balance-scale text-white"></i>
          </div>
          <div>
            <p class="text-[#1a1814] font-black text-base leading-none">مكتب العدالة</p>
            <p class="text-[#b89428] text-[10px] font-bold uppercase tracking-[0.2em]">Law Firm Pro</p>
          </div>
        </div>

        <!-- Heading -->
        <div class="mb-10">
          <h1 class="text-[#1a1814] font-black text-2xl mb-2">مرحباً بعودتك</h1>
          <p class="text-[#a89c8a] text-sm font-medium">سجّل دخولك للوصول إلى لوحة التحكم</p>
        </div>

        <!-- Error Alert -->
        <div v-if="form.errors.email || form.errors.password || errorMessage"
          class="mb-6 flex items-start gap-3 p-4 bg-red-500/8 border border-red-400/20 rounded-2xl">
          <div class="w-6 h-6 rounded-lg bg-red-500/15 flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fas fa-exclamation text-red-500 text-[10px]"></i>
          </div>
          <div>
            <p class="text-red-500 text-xs font-bold">{{ form.errors.email || form.errors.password || errorMessage }}</p>
          </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitLogin" class="space-y-5">

          <!-- Email -->
          <div>
            <label class="block text-[10px] font-black text-[#a89c8a] uppercase tracking-[0.15em] mb-2">
              البريد الإلكتروني
            </label>
            <div class="relative">
              <div class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-black/4 flex items-center justify-center">
                <i class="fas fa-envelope text-[#b0a898] text-xs"></i>
              </div>
              <input
                v-model="form.email"
                type="email"
                placeholder="your@email.com"
                autocomplete="email"
                :class="form.errors.email ? 'border-red-400/50 focus:border-red-400' : 'border-[#e2ddd5] focus:border-[#b89428]'"
                class="w-full bg-white border-[1.5px] rounded-2xl pr-14 pl-5 py-4 text-[#1a1814] text-sm font-medium outline-none transition-all placeholder:text-[#c5bdb0] focus:bg-white focus:shadow-[0_0_0_4px_rgba(180,148,40,0.08)]"
              />
            </div>
          </div>

          <!-- Password -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-[10px] font-black text-[#a89c8a] uppercase tracking-[0.15em]">
                كلمة المرور
              </label>
              <a href="/forgot-password" class="text-[11px] font-bold text-[#b89428]/70 hover:text-[#b89428] transition-colors">
                نسيت كلمة المرور؟
              </a>
            </div>
            <div class="relative">
              <div class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-black/4 flex items-center justify-center">
                <i class="fas fa-lock text-[#b0a898] text-xs"></i>
              </div>
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="••••••••"
                autocomplete="current-password"
                :class="form.errors.password ? 'border-red-400/50 focus:border-red-400' : 'border-[#e2ddd5] focus:border-[#b89428]'"
                class="w-full bg-white border-[1.5px] rounded-2xl pr-14 pl-12 py-4 text-[#1a1814] text-sm font-medium outline-none transition-all placeholder:text-[#c5bdb0] focus:bg-white focus:shadow-[0_0_0_4px_rgba(180,148,40,0.08)]"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-black/4 flex items-center justify-center text-[#b0a898] hover:text-[#6b6455] transition-colors">
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
              </button>
            </div>
          </div>

          <!-- Remember me -->
          <div class="flex items-center gap-3">
            <button
              type="button"
              @click="form.remember = !form.remember"
              :class="form.remember ? 'bg-[#b89428] border-[#b89428]' : 'bg-white border-[#d4cfc7]'"
              class="w-5 h-5 rounded-[5px] border-2 flex items-center justify-center transition-all flex-shrink-0">
              <i v-if="form.remember" class="fas fa-check text-white text-[8px]"></i>
            </button>
            <span class="text-[#888070] text-sm font-medium cursor-pointer select-none" @click="form.remember = !form.remember">
              تذكّرني على هذا الجهاز
            </span>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-4 bg-[#b89428] hover:bg-[#9e7e20] text-white font-black text-sm rounded-2xl transition-all flex items-center justify-center gap-3 disabled:opacity-60 disabled:cursor-not-allowed shadow-lg shadow-[#b89428]/22 hover:shadow-[#b89428]/30 hover:-translate-y-0.5 active:translate-y-0">
            <i v-if="form.processing" class="fas fa-spinner fa-spin text-sm"></i>
            <i v-else class="fas fa-arrow-left text-sm"></i>
            {{ form.processing ? 'جاري التحقق...' : 'تسجيل الدخول' }}
          </button>

        </form>

        <!-- Divider -->
        <div class="flex items-center gap-4 my-8">
          <div class="flex-1 h-px bg-[#ddd9d0]"></div>
          <span class="text-[#c5bdb0] text-xs font-bold">أو</span>
          <div class="flex-1 h-px bg-[#ddd9d0]"></div>
        </div>

        <!-- Support -->
        <div class="flex items-center justify-center gap-2 p-4 bg-white border border-[#e8e3da] rounded-2xl">
          <div class="w-7 h-7 rounded-lg bg-[#25D366]/10 border border-[#25D366]/20 flex items-center justify-center">
            <i class="fab fa-whatsapp text-[#25D366] text-xs"></i>
          </div>
          <p class="text-[#a89c8a] text-xs font-medium">
            مشكلة في الدخول؟
            <a href="https://wa.me/201234567890" target="_blank" class="text-[#b89428]/70 hover:text-[#b89428] font-bold transition-colors mr-1">
              تواصل مع الدعم الفني
            </a>
          </p>
        </div>

        <!-- Footer -->
        <p class="text-center text-[#c5bdb0] text-[11px] font-medium mt-8">
          © {{ new Date().getFullYear() }} مكتب العدالة للمحاماة — جميع الحقوق محفوظة
        </p>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'

defineProps({
  status: String,
})

const showPassword  = ref(false)
const errorMessage  = ref('')

const form = useForm({
  email:    '',
  password: '',
  remember: false,
})

const submitLogin = () => {
  errorMessage.value = ''
  form.post('/login/store', {
    onSuccess: () => {
      // Laravel Inertia will redirect automatically
    },
    onError: () => {
      errorMessage.value = 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
    },
  })
}

const features = [
  { icon: 'fas fa-folder-open', text: 'إدارة القضايا والجلسات بشكل كامل' },
  { icon: 'fas fa-users',       text: 'سجل شامل لبيانات الموكلين' },
  { icon: 'fas fa-coins',       text: 'متابعة الأتعاب والمصاريف لحظةً بلحظة' },
  { icon: 'fas fa-shield-alt',  text: 'حماية كاملة للبيانات والسجلات' },
]

const stats = [
  { num: '٥٠٠+', label: 'قضية' },
  { num: '٣٠٠+', label: 'موكل' },
  { num: '١٥',   label: 'سنة خبرة' },
]
</script>

<style scoped>
input { caret-color: #b89428; }

input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
  -webkit-text-fill-color: #1a1814;
  -webkit-box-shadow: 0 0 0px 1000px #ffffff inset;
  transition: background-color 5000s ease-in-out 0s;
  caret-color: #b89428;
}
</style>
