import type { Skill } from "../lib/types";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";

const CATEGORY_ORDER = [
  "AI-Assisted Development",
  "Backend",
  "Frontend",
  "Database",
  "Tools",
];

export function Skills({
  skills,
  loading,
}: {
  skills: Skill[];
  loading: boolean;
}) {
  const { t } = useLocale();
  const grouped = skills.reduce<Record<string, Skill[]>>((acc, skill) => {
    (acc[skill.category] ??= []).push(skill);
    return acc;
  }, {});

  const categories = Object.keys(grouped).sort(
    (a, b) => CATEGORY_ORDER.indexOf(a) - CATEGORY_ORDER.indexOf(b),
  );

  return (
    <section id="skills" className="bg-band px-6 py-24">
      <div className="mx-auto max-w-6xl">
        <SectionHeading
          eyebrow={t("skills.eyebrow")}
          title={t("skills.title")}
        />

        {loading ? (
          <div className="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
            {Array.from({ length: 5 }).map((_, i) => (
              <div
                key={i}
                className="h-40 animate-pulse rounded-2xl border border-border bg-surface"
              />
            ))}
          </div>
        ) : (
          <div className="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
            {categories.map((category) => {
              const isHighlight = category === "AI-Assisted Development";
              return (
                <div
                  key={category}
                  className={`reveal rounded-2xl border p-6 ${
                    isHighlight
                      ? "border-accent/40 bg-accent/5"
                      : "border-border bg-surface"
                  }`}
                >
                  <h3 className="mb-4 font-mono text-sm font-semibold uppercase tracking-wide text-accent">
                    {category}
                  </h3>
                  <ul className="flex flex-wrap gap-2">
                    {grouped[category].map((skill) => (
                      <li
                        key={skill.id}
                        className="rounded-full bg-surface-2 px-3 py-1 text-sm text-text"
                      >
                        {skill.name}
                      </li>
                    ))}
                  </ul>
                </div>
              );
            })}
          </div>
        )}
      </div>
    </section>
  );
}
