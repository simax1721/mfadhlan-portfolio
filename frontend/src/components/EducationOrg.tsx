import type { EducationEntry, OrganizationEntry } from "../lib/types";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";

export function EducationOrg({
  education,
  organizations,
  loading,
}: {
  education: EducationEntry[];
  organizations: OrganizationEntry[];
  loading: boolean;
}) {
  const { t } = useLocale();

  return (
    <section id="education" className="mx-auto max-w-4xl px-6 py-24">
      <SectionHeading
        eyebrow={t("education.eyebrow")}
        title={t("education.title")}
      />

      {loading ? (
        <div className="grid gap-6 sm:grid-cols-2">
          {Array.from({ length: 2 }).map((_, i) => (
            <div
              key={i}
              className="h-40 animate-pulse rounded-2xl border border-border bg-surface"
            />
          ))}
        </div>
      ) : (
        <div className="grid gap-6 sm:grid-cols-2">
          <div className="reveal rounded-2xl border border-border bg-surface p-6">
            <h3 className="mb-4 font-mono text-sm font-semibold uppercase tracking-wide text-accent">
              {t("education.educationLabel")}
            </h3>
            <div className="space-y-4">
              {education.map((edu) => (
                <div key={edu.id}>
                  <p className="font-medium text-heading">{edu.degree}</p>
                  <p className="text-sm text-text-dim">{edu.institution}</p>
                  <p className="font-mono text-xs text-text-dim">
                    {edu.period}
                  </p>
                </div>
              ))}
            </div>
          </div>

          <div className="reveal rounded-2xl border border-border bg-surface p-6">
            <h3 className="mb-4 font-mono text-sm font-semibold uppercase tracking-wide text-accent">
              {t("education.organizationLabel")}
            </h3>
            <div className="space-y-4">
              {organizations.map((org) => (
                <div key={org.id}>
                  <p className="font-medium text-heading">{org.role}</p>
                  <p className="text-sm text-text-dim">
                    {org.organization} &middot; {org.year}
                  </p>
                  {org.description && (
                    <p className="mt-1 text-sm text-text">
                      {org.description}
                    </p>
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>
      )}
    </section>
  );
}
