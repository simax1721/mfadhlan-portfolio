import type { Experience as ExperienceType } from "../lib/types";
import { Badge } from "./Badge";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";

export function Experience({
  experiences,
  loading,
}: {
  experiences: ExperienceType[];
  loading: boolean;
}) {
  const { t } = useLocale();

  return (
    <section id="experience" className="mx-auto max-w-4xl px-6 py-24">
      <SectionHeading
        eyebrow={t("experience.eyebrow")}
        title={t("experience.title")}
      />

      {loading ? (
        <div className="space-y-8">
          {Array.from({ length: 2 }).map((_, i) => (
            <div
              key={i}
              className="h-48 animate-pulse rounded-2xl border border-border bg-surface"
            />
          ))}
        </div>
      ) : (
        <ol className="relative space-y-10 border-l border-border pl-8">
          {experiences.map((exp) => (
            <li key={exp.id} className="reveal">
              <span className="absolute -left-[7px] mt-1.5 h-3 w-3 rounded-full bg-accent shadow-[0_0_0_4px_var(--color-bg)]" />
              <div className="rounded-2xl border border-border bg-surface p-6 sm:p-8">
                <div className="flex flex-wrap items-baseline justify-between gap-2">
                  <h3 className="text-lg font-semibold text-heading">
                    {exp.title}
                  </h3>
                  <span className="font-mono text-xs text-text-dim">
                    {exp.period}
                  </span>
                </div>
                <p className="mt-1 text-sm font-medium text-accent">
                  {exp.company}
                </p>

                <ul className="mt-4 space-y-2 text-sm text-text">
                  {exp.bullets.map((bullet, i) => (
                    <li key={i} className="flex gap-2">
                      <span className="mt-2 h-1 w-1 shrink-0 rounded-full bg-text-dim" />
                      <span>{bullet}</span>
                    </li>
                  ))}
                </ul>

                {exp.tech_stack.length > 0 && (
                  <div className="mt-5 flex flex-wrap gap-2">
                    {exp.tech_stack.map((tech) => (
                      <Badge key={tech}>{tech}</Badge>
                    ))}
                  </div>
                )}
              </div>
            </li>
          ))}
        </ol>
      )}
    </section>
  );
}
