import type { Profile } from "../lib/types";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";
import { SectionBackground } from "./SectionBackground";

export function About({ profile }: { profile: Profile }) {
  const { t } = useLocale();

  return (
    <section
      id="about"
      className="relative isolate overflow-hidden px-6 py-16 sm:py-20 md:py-24"
    >
      <SectionBackground />
      <div className="mx-auto max-w-4xl">
        <SectionHeading eyebrow={t("about.eyebrow")} title={t("about.title")} />
        <div className="reveal rounded-2xl border border-border bg-surface p-8 sm:p-10">
          <p className="text-balance text-center text-base leading-relaxed text-text sm:text-lg">
            {profile.summary}
          </p>
        </div>
      </div>
    </section>
  );
}
