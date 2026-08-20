import type { Project } from "../lib/types";
import { Badge } from "./Badge";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";

/** CSS-only "browser window" mockup shown when a project has no screenshot. */
function ProjectPlaceholder() {
  return (
    <div
      className="flex h-full w-full flex-col items-center justify-center gap-3 p-6"
      aria-hidden="true"
    >
      <div className="w-full max-w-[200px] overflow-hidden rounded-lg border border-border bg-bg shadow-sm">
        <div className="flex items-center gap-1.5 border-b border-border bg-surface-2 px-2.5 py-1.5">
          <span className="h-1.5 w-1.5 rounded-full bg-text-dim/40" />
          <span className="h-1.5 w-1.5 rounded-full bg-text-dim/40" />
          <span className="h-1.5 w-1.5 rounded-full bg-text-dim/40" />
          <span className="ml-1.5 h-1.5 flex-1 rounded-full bg-border" />
        </div>
        <div className="space-y-1.5 p-3">
          <span className="block h-1.5 w-3/4 rounded-full bg-accent/35" />
          <span className="block h-1.5 w-full rounded-full bg-border" />
          <span className="block h-1.5 w-5/6 rounded-full bg-border" />
          <span className="block h-1.5 w-2/3 rounded-full bg-accent-2/35" />
        </div>
      </div>
    </div>
  );
}

function ProjectCard({ project }: { project: Project }) {
  const { t } = useLocale();

  return (
    <article className="project-card reveal group flex flex-col overflow-hidden rounded-2xl border border-border bg-surface transition-all hover:-translate-y-1 hover:border-accent/50">
      <div className="relative flex aspect-video items-center justify-center overflow-hidden border-b border-border bg-surface-2">
        {project.image_url ? (
          <img
            src={project.image_url}
            alt={project.title}
            className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
          />
        ) : (
          <ProjectPlaceholder />
        )}
        {project.featured && (
          <span className="absolute right-3 top-3 rounded-full bg-amber px-3 py-1 text-xs font-semibold text-slate-900 shadow-sm">
            {t("projects.featured")}
          </span>
        )}
      </div>

      <div className="flex flex-1 flex-col p-6">
        <h3 className="text-lg font-semibold text-heading">
          {project.title}
        </h3>
        {project.subtitle && (
          <p className="mt-1 text-sm text-accent">{project.subtitle}</p>
        )}
        {project.description && (
          <p className="mt-3 text-sm leading-relaxed text-text-dim">
            {project.description}
          </p>
        )}

        {project.bullets.length > 0 && (
          <ul className="mt-4 space-y-1.5 text-sm text-text">
            {project.bullets.map((bullet, i) => (
              <li key={i} className="flex gap-2">
                <span className="mt-2 h-1 w-1 shrink-0 rounded-full bg-text-dim" />
                <span>{bullet}</span>
              </li>
            ))}
          </ul>
        )}

        <div className="mt-5 flex flex-wrap gap-2">
          {project.tech_stack.map((tech) => (
            <Badge key={tech}>{tech}</Badge>
          ))}
        </div>

        <div className="mt-6 flex gap-4 text-sm font-medium">
          {project.demo_url ? (
            <a
              href={project.demo_url}
              target="_blank"
              rel="noreferrer"
              className="text-accent hover:underline"
            >
              {t("projects.liveDemo")}
            </a>
          ) : (
            <span className="text-text-dim">{t("projects.demoSoon")}</span>
          )}
          {project.github_url && (
            <a
              href={project.github_url}
              target="_blank"
              rel="noreferrer"
              className="text-text-dim hover:text-accent"
            >
              {t("projects.source")}
            </a>
          )}
        </div>
      </div>
    </article>
  );
}

export function Projects({
  projects,
  loading,
}: {
  projects: Project[];
  loading: boolean;
}) {
  const { t } = useLocale();

  return (
    <section id="projects" className="mx-auto max-w-6xl px-6 py-24">
      <SectionHeading
        eyebrow={t("projects.eyebrow")}
        title={t("projects.title")}
      />

      {loading ? (
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {Array.from({ length: 3 }).map((_, i) => (
            <div
              key={i}
              className="h-96 animate-pulse rounded-2xl border border-border bg-surface"
            />
          ))}
        </div>
      ) : projects.length === 0 ? (
        <p className="text-center text-text-dim">{t("projects.empty")}</p>
      ) : (
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {projects.map((project) => (
            <ProjectCard key={project.id} project={project} />
          ))}
        </div>
      )}
    </section>
  );
}
