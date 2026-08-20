import type { Profile } from "../lib/types";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";

export function About({ profile }: { profile: Profile }) {
  const { t } = useLocale();

  return (
    <section id="about" className="mx-auto max-w-4xl px-6 py-24">
      <SectionHeading eyebrow={t("about.eyebrow")} title={t("about.title")} />
      <div className="reveal rounded-2xl border border-border bg-surface p-8 sm:p-10">
        <p className="text-balance text-center text-base leading-relaxed text-text sm:text-lg">
          {profile.summary}
        </p>
      </div>
    </section>
  );
}
