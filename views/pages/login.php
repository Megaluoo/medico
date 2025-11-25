<section class="grid md:grid-cols-2 gap-10 items-center">
    <div class="space-y-4">
        <p class="text-sm uppercase tracking-[0.3em] text-emerald-300/80">Acceso seguro</p>
        <h2 class="text-4xl font-bold text-slate-50">Bienvenido de nuevo</h2>
        <p class="text-slate-300 text-lg leading-relaxed">
            Ingresa tus credenciales para acceder al panel médico. Mantén tus datos seguros con nuestra autenticación protegida y sesiones endurecidas.
        </p>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 shadow-lg shadow-emerald-500/10">
                <p class="text-3xl font-semibold text-emerald-300">2x</p>
                <p class="text-sm text-slate-400">Protección con cookies seguras</p>
            </div>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 shadow-lg shadow-emerald-500/10">
                <p class="text-3xl font-semibold text-emerald-300">24/7</p>
                <p class="text-sm text-slate-400">Monitoreo de sesiones</p>
            </div>
        </div>
    </div>

    <div class="p-8 rounded-3xl bg-white/10 border border-white/10 backdrop-blur shadow-2xl shadow-emerald-500/20">
        <form action="/login" method="POST" class="space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-200">Correo electrónico</label>
                <div class="relative">
                    <input type="email" name="email" value="<?php echo $email ?? ''; ?>" required
                        class="w-full px-4 py-3 rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100 focus:ring-2 focus:ring-emerald-400 focus:border-transparent placeholder:text-slate-500"
                        placeholder="tucorreo@hospital.com">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-300 text-sm">@</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-200">Contraseña</label>
                <div class="relative">
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100 focus:ring-2 focus:ring-emerald-400 focus:border-transparent placeholder:text-slate-500"
                        placeholder="••••••••">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-300 text-sm">&#128274;</span>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/40 text-red-200">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-400 text-slate-900 font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-emerald-400/40 transition transform hover:-translate-y-0.5">
                Entrar al panel
            </button>

            <p class="text-sm text-slate-400 text-center">Usuarios de prueba: ana@example.com o carlos@example.com / <span class="text-emerald-200">password123</span></p>
        </form>
    </div>
</section>
