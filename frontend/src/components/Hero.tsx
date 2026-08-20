import type { Profile } from "../lib/types";
import { useLocale } from "../i18n/useLocale";
import { api } from "../lib/api";

export function Hero({ profile }: { profile: Profile }) {
  const { t, locale } = useLocale();

  return (
    <section
      id="top"
      className="relative overflow-hidden px-6 pb-24 pt-20 md:pt-32"
    >
      <div className="dot-grid -z-10" aria-hidden="true" />
      <div
        className="pointer-events-none absolute inset-0 -z-10"
        style={{
          background:
            "radial-gradient(600px circle at 50% 0%, color-mix(in srgb, var(--color-accent-2) 15%, transparent), transparent 70%)," +
            "radial-gradient(420px circle at 85% 15%, color-mix(in srgb, var(--color-accent) 12%, transparent), transparent 70%)",
        }}
      />

      <div className="mx-auto max-w-4xl text-center">
        <p className="reveal mb-4 inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-1.5 text-sm text-text-dim">
          <span className="h-2 w-2 animate-pulse rounded-full bg-accent" />
          {t("hero.openToRemote", { location: profile.location })}
        </p>

        <h1 className="reveal text-4xl font-bold tracking-tight text-heading sm:text-5xl md:text-6xl">
          {t("hero.greeting")}{" "}
          <span className="text-gradient">{profile.name}</span>
        </h1>

        <h2 className="reveal mt-3 font-mono text-lg text-accent sm:text-xl">
          {profile.role}
        </h2>

        <p className="reveal mx-auto mt-6 max-w-2xl text-balance text-base leading-relaxed text-text-dim sm:text-lg">
          {profile.tagline}
        </p>

        <div className="reveal mt-10 flex flex-wrap items-center justify-center gap-4">
          <a href="#projects" className="btn-primary">
            {t("hero.viewProjects")}
          </a>
          <a href={api.cvUrl(locale)} className="btn-secondary">
            {t("hero.downloadCV")}
          </a>
          {profile.github_url && (
            <a
              href={profile.github_url}
              target="_blank"
              rel="noreferrer"
              className="btn-secondary"
            >
              {t("hero.github")}
            </a>
          )}
          {profile.linkedin_url && (
            <a
              href={profile.linkedin_url}
              target="_blank"
              rel="noreferrer"
              className="btn-secondary"
            >
              {t("hero.linkedin")}
            </a>
          )}
        </div>
      </div>
    </section>
  );
}
